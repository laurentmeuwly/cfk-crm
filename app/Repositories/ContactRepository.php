<?php

namespace App\Repositories;

use App\Models\Contact;
use App\Models\Title;
use Illuminate\Support\Str;

class ContactRepository
{
    /**
     * @param $email
     * @param $name
     * @param $firstnae
     */
    public function createOrUpdate($email, $lastname, $firstname, $title, $language, $newsletter): void
    {
        /** @var Contact $contact */
        /*$contact = Contact::withTrashed()
            ->where('email', $email)
            ->first();*/

        if(!in_array(Str::upper($language), config('cfk.locales'))) {
            $language = 'FR';
        }

        $existingTitle = Title::where([
                                        ['name', $title],
                                        ['locale', $language]
                                ])->first();
        if( $existingTitle ) {
            $title_id = $existingTitle->id;
        } else {
            $title_id = 1;
        }

        $contact = Contact::updateOrCreate(
            ['email' => $email],
            [
                'email' => Str::lower($email),
                'lastname' => Str::title($lastname),
                'firstname' => Str::title($firstname),
                'prefered_language' => Str::upper($language),
                'newsletter' => $newsletter,
                'title_id' => $title_id,
                'source_id' => 6,
            ]
        );
    }
}