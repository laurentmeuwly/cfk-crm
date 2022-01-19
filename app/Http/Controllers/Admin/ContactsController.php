<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Contact\BulkDestroyContact;
use App\Http\Requests\Admin\Contact\DestroyContact;
use App\Http\Requests\Admin\Contact\ImportContact;
use App\Http\Requests\Admin\Contact\IndexContact;
use App\Http\Requests\Admin\Contact\PurgeContact;
use App\Http\Requests\Admin\Contact\StoreContact;
use App\Http\Requests\Admin\Contact\UpdateContact;
use App\Models\Category;
use App\Models\Contact;
use App\Models\Source;
use App\Models\Title;
use App\Service\Import\ContactService;
use Brackets\AdminListing\Facades\AdminListing;
use Carbon\Carbon;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

use LMeuwly\Sendinblue\SendinblueApi;

use LMeuwly\Sendinblue\SendinblueFacade as Sendinblue;

class ContactsController extends Controller
{
    protected $contactService;

    public function __construct(
        ContactService $contactService
    ) {
        $this->contactService = $contactService;
    }

    /**
     * Display a listing of the resource.
     *
     * @param IndexContact $request
     * @return array|Factory|View
     */
    public function index(IndexContact $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Contact::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'firstname', 'lastname', 'email', 'prefered_language', 'newsletter', 'deleted_at', 'title_id', 'source_id'],

            // set columns to searchIn
            ['id', 'firstname', 'lastname', 'email', 'prefered_language'],

            function ($query) use ($request) {
                if ($request->input('deleted')=='true') {
                    $query->onlyTrashed()->with(['title']);
                } else {
                    $query->with(['title']);
                }

                // add this line if you want to search by title attributes
                $query->join('titles', 'titles.id', '=', 'contacts.title_id');

                if ($request->has('titles')) {
                    $query->whereIn('title_id', $request->get('titles'));
                }
            }
        );

        if ($request->ajax()) {
            /*if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }*/
            return ['data' => $data];
        }

        return view('admin.contact.index', [
            'data' => $data,
            'titles' => Title::all(),
            'sources' => Source::all()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.contact.create');

        return view('admin.contact.create', [
            'titles' => Title::all(),
            'sources' => Source::all(),
            'categories' => Category::all(),
            'languages' => json_encode(config('cfk.locales'))
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreContact $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreContact $request)
    {
        // Sanitize input
        //$sanitized = $request->getSanitized();
        $sanitized = $request->validated();

        $sanitized['title_id'] = $request->getTitleId();
        $sanitized['source_id'] = $request->getSourceId();
        $sanitized['categories'] = $request->getCategories();

        DB::transaction(function () use ($sanitized) {
            // Store the Contact
            $contact = Contact::create($sanitized);
            $contact->categories()->sync($sanitized['categories']);
        });

        if ($request->ajax()) {
            return ['redirect' => url('admin/contacts'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/contacts');
    }

    /**
     * Display the specified resource.
     *
     * @param Contact $contact
     * @throws AuthorizationException
     * @return void
     */
    public function show(Contact $contact)
    {
        $this->authorize('admin.contact.show', $contact);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Contact $contact
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Contact $contact)
    {
        $this->authorize('admin.contact.edit', $contact);

        $contact->load(['title', 'source', 'categories']);

        return view('admin.contact.edit', [
            'contact' => $contact,
            'titles' => Title::all(),
            'sources' => Source::all(),
            'categories' => Category::all(),
            'languages' => json_encode(config('cfk.locales'))
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateContact $request
     * @param Contact $contact
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateContact $request, Contact $contact)
    {
        // Sanitize input
        $sanitized = $request->validated();

        $sanitized['title_id'] = $request->getTitleId();
        $sanitized['source_id'] = $request->getSourceId();
        $sanitized['categories'] = $request->getCategories();

        DB::transaction(function () use ($contact, $sanitized) {
            // Update changed values Contact
            $contact->update($sanitized);
            $contact->categories()->sync($sanitized['categories']);
        });

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/contacts'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/contacts');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyContact $request
     * @param Contact $contact
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyContact $request, Contact $contact)
    {
        $contact->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyContact $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyContact $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    DB::table('contacts')->whereIn('id', $bulkChunk)
                        ->update([
                            'deleted_at' => Carbon::now()->format('Y-m-d H:i:s')
                    ]);

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function restore(Contact $contact)
    {
        $contact->restore();
        return redirect('admin/contacts')->with('success', trans('brackets/admin-ui::admin.operation.succeeded'));
        //return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }

    public function purge(PurgeContact $request, Contact $contact)
    {
        $contact->forceDelete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect('admin/contacts?deleted=true');
    }

    /**
     * @param ImportContact $request
     * @return array|JsonResponse|mixed
     */
    public function import(ImportContact $request)
    {
        $numberOfConflicts = 0;

        if ($request->hasFile('fileImport')) {

            try {
                $collectionFromImportedFile = $this->contactService->getCollectionFromImportedFile($request->file('fileImport'));
            } catch (Exception $e) {
                return response()->json($e->getMessage(), 409);
            }

            $existingContacts = $this->contactService->getAllContacts();

            if ($request->input('onlyMissing') === 'true') {
                $filteredCollection = $this->contactService->getFilteredExistingContacts($collectionFromImportedFile, $existingContacts);
                $this->contactService->saveCollection($filteredCollection);

                return ['numberOfImportedContacts' => count($filteredCollection), 'numberOfUpdatedContacts' => 0];
            } else {
                $collectionWithConflicts = $this->contactService->getCollectionWithConflicts($collectionFromImportedFile, $existingContacts);

                $numberOfConflicts = $this->contactService->getNumberOfConflicts($collectionWithConflicts);

                if ($numberOfConflicts === 0) {
                    return $this->contactService->checkAndUpdateContacts($existingContacts, $collectionWithConflicts);
                }

                return $collectionWithConflicts;
            }

        }
        return response()->json('No file imported', 409);
    }

    public function showToken()
    {
        echo csrf_token();
    }
}
