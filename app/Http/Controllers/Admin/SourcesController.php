<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Source\BulkDestroySource;
use App\Http\Requests\Admin\Source\DestroySource;
use App\Http\Requests\Admin\Source\IndexSource;
use App\Http\Requests\Admin\Source\StoreSource;
use App\Http\Requests\Admin\Source\UpdateSource;
use App\Models\Source;
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

class SourcesController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @param IndexSource $request
     * @return array|Factory|View
     */
    public function index(IndexSource $request)
    {
        // create and AdminListing instance for a specific model and
        $data = AdminListing::create(Source::class)->processRequestAndGet(
            // pass the request with params
            $request,

            // set columns to query
            ['id', 'name'],

            // set columns to searchIn
            ['id', 'name'],

            function ($query) {
                $query->withCount('contacts');
            }
        );

        if ($request->ajax()) {
            if ($request->has('bulk')) {
                return [
                    'bulkItems' => $data->pluck('id')
                ];
            }
            return ['data' => $data];
        }

        return view('admin.source.index', ['data' => $data]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function create()
    {
        $this->authorize('admin.source.create');

        return view('admin.source.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StoreSource $request
     * @return array|RedirectResponse|Redirector
     */
    public function store(StoreSource $request)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Store the Source
        $source = Source::create($sanitized);

        if ($request->ajax()) {
            return ['redirect' => url('admin/sources'), 'message' => trans('brackets/admin-ui::admin.operation.succeeded')];
        }

        return redirect('admin/sources');
    }

    /**
     * Display the specified resource.
     *
     * @param Source $source
     * @throws AuthorizationException
     * @return void
     */
    public function show(Source $source)
    {
        $this->authorize('admin.source.show', $source);

        // TODO your code goes here
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Source $source
     * @throws AuthorizationException
     * @return Factory|View
     */
    public function edit(Source $source)
    {
        $this->authorize('admin.source.edit', $source);


        return view('admin.source.edit', [
            'source' => $source,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param UpdateSource $request
     * @param Source $source
     * @return array|RedirectResponse|Redirector
     */
    public function update(UpdateSource $request, Source $source)
    {
        // Sanitize input
        $sanitized = $request->getSanitized();

        // Update changed values Source
        $source->update($sanitized);

        if ($request->ajax()) {
            return [
                'redirect' => url('admin/sources'),
                'message' => trans('brackets/admin-ui::admin.operation.succeeded'),
            ];
        }

        return redirect('admin/sources');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param DestroySource $request
     * @param Source $source
     * @throws Exception
     * @return ResponseFactory|RedirectResponse|Response
     */
    public function destroy(DestroySource $request, Source $source)
    {
        $source->delete();

        if ($request->ajax()) {
            return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
        }

        return redirect()->back();
    }

    /**
     * Remove the specified resources from storage.
     *
     * @param BulkDestroySource $request
     * @throws Exception
     * @return Response|bool
     */
    public function bulkDestroy(BulkDestroySource $request) : Response
    {
        DB::transaction(static function () use ($request) {
            collect($request->data['ids'])
                ->chunk(1000)
                ->each(static function ($bulkChunk) {
                    Source::whereIn('id', $bulkChunk)->delete();

                    // TODO your code goes here
                });
        });

        return response(['message' => trans('brackets/admin-ui::admin.operation.succeeded')]);
    }
}
