<?php

namespace App\Service\Import;

use App\Imports\ContactsImport;
use App\Models\Contact;
use App\Repositories\ContactRepository;
use Exception;
use Illuminate\Support\Collection;

class ContactService
{
    /**
     * @var ContactRepository
     */
    protected $contactRepository;

    /**
     * ContactService constructor.
     *
     * @param ContactRepository $contactRepository
     */
    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @param Collection $filteredCollection
     */
    public function saveCollection(Collection $filteredCollection): void
    {
        $filteredCollection->each(function ($item) {
            $this->contactRepository->createOrUpdate($item['email'], $item['nom'], $item['prenom'], $item['civilite'], $item['langue'], $item['newsletter']);
        });
    }

    /**
     * @param $row
     * @return string
     */
    public function buildKeyForArray($row): string
    {
        return $row['email'];
    }

    /**
     * @param $row
     * @param $array
     * @return bool
     */
    public function rowExistsInArray($row, $array): bool
    {
        return array_key_exists($this->buildKeyForArray($row), $array);
    }

    /**
     * @param $row
     * @param $array
     * @param $choosenLanguage
     * @return bool
     */
    public function rowValueEqualsValueInArray($row, $array, $choosenLanguage): bool
    {
        if (!empty($array[$this->buildKeyForArray($row)]['text'])) {
            if (isset($array[$this->buildKeyForArray($row)]['text'][$choosenLanguage])) {
                return $this->rowExistsInArray($row,
                        $array) && (string)$row[$choosenLanguage] === (string)$array[$this->buildKeyForArray($row)]['text'][$choosenLanguage];
            } else {
                return false;
            }
        }
        return true;
    }
    
    /**
     * @return array
     */
    public function getAllContacts(): array
    {
        return Contact::all()->keyBy('email')->toArray();
    }

    /**
     * @param $chosenLanguage
     * @param $existingContacts
     * @param $collectionToUpdate
     * @return array
     */
    public function checkAndUpdateContacts($existingContacts, $collectionToUpdate): array
    {
        $chosenLanguage='FR'; // provisoire
        $numberOfImportedContacts = 0;
        $numberOfUpdatedContacts = 0;

        $collectionToUpdate->map(function ($item) use (
            $chosenLanguage,
            $existingContacts,
            &
            $numberOfUpdatedContacts,
            &$numberOfImportedContacts
        ) {
            if (isset($existingContacts[$this->buildKeyForArray($item)]['id'])) {
                $id = $existingContacts[$this->buildKeyForArray($item)]['id'];
                $existingContactInDatabase = Contact::find($id);
                $textArray = $existingContactInDatabase->text;
                if (isset($textArray[$chosenLanguage])) {
                    if ($textArray[$chosenLanguage] !== $item[$chosenLanguage]) {
                        $numberOfUpdatedContacts++;
                        $textArray[$chosenLanguage] = $item[$chosenLanguage];
                        $existingContactInDatabase->update(['text' => $textArray]);
                    }
                } else {
                    $numberOfUpdatedContacts++;
                    $textArray[$chosenLanguage] = $item[$chosenLanguage];
                    $existingContactInDatabase->update(['text' => $textArray]);
                }
            } else {
                $numberOfImportedContacts++;
                $this->translationRepository->createOrUpdate($item['namespace'], $item['group'], $item['default'],
                    $chosenLanguage, $item[$chosenLanguage]);
            }
        });

        return [
            'numberOfImportedContacts' => $numberOfImportedContacts,
            'numberOfUpdatedContacts' => $numberOfUpdatedContacts
        ];
    }

    /**
     * @param $collectionFromImportedFile
     * @param $existingContacts
     * @param $chosenLanguage
     * @return mixed
     */
    public function getCollectionWithConflicts($collectionFromImportedFile, $existingContacts)
    {
        return $collectionFromImportedFile->map(function ($row) use ($existingContacts) {
            $chosenLanguage='FR'; // provisoire
            $row['has_conflict'] = false;
            if (!$this->rowValueEqualsValueInArray($row, $existingContacts, $chosenLanguage)) {
                $row['has_conflict'] = true;
                if (isset($existingContacts[$this->buildKeyForArray($row)])) {
                    if (isset($existingContacts[$this->buildKeyForArray($row)]['text'][$chosenLanguage])) {
                        $row['current_value'] = (string)$existingContacts[$this->buildKeyForArray($row)]['text'][$chosenLanguage];
                    } else {
                        $row['has_conflict'] = false;
                        $row['current_value'] = '';
                    }
                } else {
                    $row['current_value'] = '';
                    $row['has_conflict'] = false;
                }
            }
            return $row;
        });
    }

    /**
     * @param $collectionWithConflicts
     * @return mixed
     */
    public function getNumberOfConflicts($collectionWithConflicts)
    {        
        /*return $collectionWithConflicts->filter(static function ($row) {
            return $row['has_conflict'];
        })->count();*/

        return 2;//$collectionWithConflicts->count();
    }

    /**
     * @param $collectionFromImportedFile
     * @param $existingContacts
     * @return mixed
     */
    public function getFilteredExistingContacts($collectionFromImportedFile, $existingContacts)
    {
        return $collectionFromImportedFile->reject(function ($row) use ($existingContacts) {
            // filter out rows representing contacts existing in the database (treat deleted_at as non-existing)
            return $this->rowExistsInArray($row, $existingContacts);
        });
    }

    /**
     * @param $collectionToImport
     * @return bool
     */
    public function validImportFile($collectionToImport): bool
    {
        // header should be given in lowercase as the collection uses the str_slug helper
        $requiredHeaders = ['civilite', 'prenom', 'nom', 'email'];

        foreach ($requiredHeaders as $item) {            
            if (!isset($collectionToImport->first()[$item])) {
                return false;
            }
        }

        return true;
    }

    /**
     * @param $file
     * @return mixed
     */
    public function getCollectionFromImportedFile($file)
    {
        if ($file->getClientOriginalExtension() !== 'xlsx') {
            abort(409, 'Unsupported file type');
        }

        try {
            $collectionFromImportedFile = (new ContactsImport())->toCollection($file)->first();

            if (!$this->validImportFile($collectionFromImportedFile)) {
                abort(409, 'Wrong syntax in your import');
            }

            return $collectionFromImportedFile;
        } catch (Exception $e) {
            abort(409, 'Probably wrong syntax in your import');
        }
    }
}