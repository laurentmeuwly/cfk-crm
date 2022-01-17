<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Title\BulkDestroyTitle;
use App\Http\Requests\Admin\Title\DestroyTitle;
use App\Http\Requests\Admin\Title\IndexTitle;
use App\Http\Requests\Admin\Title\StoreTitle;
use App\Http\Requests\Admin\Title\UpdateTitle;
use App\Models\Title;
use Brackets\AdminListing\Facades\AdminListing;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TitlesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexTitle $request
     * @return array|Factory|View
     */
    public function index(IndexTitle $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Title::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name', 'locale'],

            // set columns to searchIn
            ['id', 'name', 'locale']
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.title.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.title.create');

        return view('admin.title.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreTitle $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreTitle $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Title
        $title = Title::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/titles'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/titles');
    }

    /**
     * Display the specified resource.
     *
     * @param Title $title
     * @throws AuthorizationException
     * @return void
     */
    public function show(Title $title)
    {
        $this->authorize('admin.title.show', $title);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Title $title
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Title $title)
    {
        $this->authorize('admin.title.edit', $title);


        return view('admin.title.edit', [
            'title' => $title,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateTitle $request
     * @param Title $title
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateTitle $request, Title $title)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Title
        $title->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/titles'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/titles');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroyTitle $request
     * @param Title $title
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroyTitle $request, Title $title)
    {
        $title->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroyTitle $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroyTitle $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Title::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
