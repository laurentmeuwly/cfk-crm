@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.contact.actions.index'))

@section('body')

    <contact-listing
        :data="{{ $data->toJson() }}"
        :url="'{{ url('admin/contacts') }}'"
        inline-template>

        <div class="row">
            <div class="col">

                <modal @closed="onCloseImportModal()" name="import-contact" class="modal--contact" v-cloak
                       height="auto" :scrollable="true" :adaptive="true" :pivot-y="0.25">
                    <h4 class="modal-title">{{ trans('contacts.import.title') }}</h4>
                    <div class="modal-body">
                        <div v-show="currentStep == 1">
                            <form>
                                <p class="col-md-12">{{ trans('contact.import.notice') }}</p>
                                <div class="row form-group col-md-12" :class="{'has-danger': errors.has('importFile')}">
                                    <div class="col-md-4 text-md-right">
                                        <label for="importFile" class="col-form-label text-md-right">{{ trans('brackets/admin-translations::admin.import.upload_file') }}</label>
                                    </div>
                                    <div class="file-field col-md-6">
                                        <div class="btn btn-primary btn-sm col-md-12 float-left">
                                            <span><span v-if="importedFile">@{{ importedFile.name }}</span><span v-else>{{ trans('brackets/admin-translations::admin.import.choose_file') }}</span></span>
                                            <input type="file" id="file" name="importFile" ref="file"
                                                   v-on:change="handleImportFileUpload"
                                                   v-validate="'mimes:application/vnd.openxmlformats-officedocument.spreadsheetml.sheet|required'">
                                        </div>
                                    </div>
                                    <span v-if="errors.has('importFile')" class="form-control-feedback form-text col-md-12" v-cloak>@{{ errors.first('importFile') }}</span>
                                </div>

                                <div class="offset-md-4 import-checkbox">
                                    <input disabled class="form-check-input" type="checkbox" value=""
                                           id="onlyMissingContacts" v-model="onlyMissing" ref="only_missing">
                                    <label class="form-check-label" for="onlyMissingContacts">
                                        {{ trans('contact.import.do_not_override') }}
                                    </label>
                                </div>
                            </form>
                        </div>
                        <div v-show="currentStep == 2" class="col-md-12">
                            <div class="text-center col-md-12">
                                <p>{{ trans('contact.import.conflict_notice_nb_contacts_found') }}<br>
                                    {{ trans('contact.import.conflict_notice_contacts_differ') }}
                                </p>
                            </div>

                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>{{ trans('contact.fields.email') }}</th>
                                    <th>{{ trans('contact.fields.name') }}</th>
                                    <th>{{ trans('contact.fields.firstname') }}</th>
                                    <th>{{ trans('contact.current_value') }}</th>
                                    <th>{{ trans('contact.fields.imported_value') }}</th>
                                    <th style="display: none;"></th>
                                </tr>
                                </thead>
                            </table>


                        </div>
                        <div v-show="currentStep == 3">
                            <div class="text-center col-md-12">
                                <p>
                                    @{{numberOfSuccessfullyImportedContacts}} {{ trans('contacts.import.successfully_notice') }}<br>
                                    @{{numberOfSuccessfullyUpdatedContacts}} {{ trans('contacts.import.successfully_notice_update') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer import-footer">
                        <button type="button" v-if="!lastStep" class="btn btn-primary col-md-2 btn-spinner"
                                :disabled="errors.any()" @click.prevent="nextStep()">Next
                        </button>
                    </div>
                </modal>



                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-align-justify"></i> {{ trans('admin.contact.actions.index') }}

                        <a class="btn btn-primary btn-sm pull-right m-b-0 ml-2" href="#" @click.prevent="showImport()"
                           role="button"><i
                                    class="fa fa-upload"></i>&nbsp; {{ trans('brackets/admin-translations::admin.btn.import') }}
                        </a>
                        <a class="btn btn-primary btn-spinner btn-sm pull-right m-b-0" href="{{ url('admin/contacts/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.contact.actions.create') }}</a>
                        @if (Request::get('deleted')=='true')
                        <a class="btn btn-warning btn-spinner btn-sm pull-right m-b-0" role="button" href="{{ url('admin/contacts') }}" style="margin-right: 5px;"><i class="fa fa-check"></i>&nbsp;{{ __('news::global.show_current') }}</a>
                        @else
                        <a class="btn btn-warning btn-spinner btn-sm pull-right m-b-0" role="button" href="{{ url('admin/contacts?deleted=true') }}" style="margin-right: 5px;"><i class="fa fa-trash"></i>&nbsp;{{ __('news::global.show_deleted') }}</a>
                        @endif

                    </div>
                    <div class="card-body" v-cloak>
                        <div class="card-block">
                            <form @submit.prevent="">
                                <div class="row justify-content-md-between">
                                    <div class="col col-lg-7 col-xl-5 form-group">
                                        <div class="input-group">
                                            <input class="form-control" placeholder="{{ trans('brackets/admin-ui::admin.placeholder.search') }}" v-model="search" @keyup.enter="filter('search', $event.target.value)" />
                                            <span class="input-group-append">
                                                <button type="button" class="btn btn-primary" @click="filter('search', search)"><i class="fa fa-search"></i>&nbsp; {{ trans('brackets/admin-ui::admin.btn.search') }}</button>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="col form-group deadline-checkbox-col">
                                        <div class="switch-filter-wrap">
                                            <label class="switch switch-3d switch-primary">
                                                <input type="checkbox" class="switch-input" v-model="showTitlesFilter" >
                                                <span class="switch-slider"></span>
                                            </label>
                                            <span class="titles-filter">&nbsp;{{ __('Titles filter') }}</span>
                                        </div>
                                    </div>

                                    <div class="col-sm-auto form-group ">
                                        <select class="form-control" v-model="pagination.state.per_page">

                                            <option value="10">10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="row" v-if="showTitlesFilter">
                                    <div class="col-sm-auto form-group" style="margin-bottom: 0;">
                                        <p style="line-height: 40px; margin:0;">{{ __('Select title/s') }}</p>
                                    </div>
                                    <div class="col col-lg-12 col-xl-12 form-group" style="max-width: 590px; ">
                                        <multiselect v-model="titlesMultiselect"
                                                    :options="{{ $titles->map(function($title) { return ['key' => $title->id, 'label' =>  $title->name]; })->toJson() }}"
                                                    label="label"
                                                    track-by="key"
                                                    placeholder="{{ __('Type to search a title/s') }}"
                                                    :limit="2"
                                                    :multiple="true">
                                        </multiselect>
                                    </div>
                                </div>
                            </form>

                            <table class="table table-hover table-listing">
                                <thead>
                                    <tr>
                                        <th class="bulk-checkbox">
                                            <input class="form-check-input" id="enabled" type="checkbox" v-model="isClickedAll" v-validate="''" data-vv-name="enabled"  name="enabled_fake_element" @click="onBulkItemsClickedAllWithPagination()">
                                            <label class="form-check-label" for="enabled">
                                                #
                                            </label>
                                        </th>


                                        <th is='sortable' :column="'title_id'">{{ trans('admin.contact.columns.title_id') }}</th>
                                        <th is='sortable' :column="'lastname'">{{ trans('admin.contact.columns.lastname') }}</th>
                                        <th is='sortable' :column="'firstname'">{{ trans('admin.contact.columns.firstname') }}</th>
                                        <th is='sortable' :column="'email'">{{ trans('admin.contact.columns.email') }}</th>
                                        <th is='sortable' :column="'prefered_language'">{{ trans('admin.contact.columns.prefered_language') }}</th>
                                        <th is='sortable' :column="'newsletter'">{{ trans('admin.contact.columns.newsletter') }}</th>


                                        <th></th>
                                    </tr>
                                    <tr v-show="(clickedBulkItemsCount > 0) || isClickedAll">
                                        <td class="bg-bulk-info d-table-cell text-center" colspan="9">
                                            <span class="align-middle font-weight-light text-dark">{{ trans('brackets/admin-ui::admin.listing.selected_items') }} @{{ clickedBulkItemsCount }}.  <a href="#" class="text-primary" @click="onBulkItemsClickedAll('/admin/contacts')" v-if="(clickedBulkItemsCount < pagination.state.total)"> <i class="fa" :class="bulkCheckingAllLoader ? 'fa-spinner' : ''"></i> {{ trans('brackets/admin-ui::admin.listing.check_all_items') }} @{{ pagination.state.total }}</a> <span class="text-primary">|</span> <a
                                                        href="#" class="text-primary" @click="onBulkItemsClickedAllUncheck()">{{ trans('brackets/admin-ui::admin.listing.uncheck_all_items') }}</a>  </span>

                                            <span class="pull-right pr-2">
                                                <button class="btn btn-sm btn-danger pr-3 pl-3" @click="bulkDelete('/admin/contacts/bulk-destroy')">{{ trans('brackets/admin-ui::admin.btn.delete') }}</button>
                                            </span>

                                        </td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(item, index) in collection" :key="item.id" :class="bulkItems[item.id] ? 'bg-bulk' : ''">
                                        <td class="bulk-checkbox">
                                            <input class="form-check-input" :id="'enabled' + item.id" type="checkbox" v-model="bulkItems[item.id]" v-validate="''" :data-vv-name="'enabled' + item.id"  :name="'enabled' + item.id + '_fake_element'" @click="onBulkItemClicked(item.id)" :disabled="bulkCheckingAllLoader">
                                            <label class="form-check-label" :for="'enabled' + item.id">
                                            </label>
                                        </td>


                                        <td v-if="item.title===null">-</td>
                                        <td v-else>@{{ item.title.name }}</td>
                                        <td>@{{ item.firstname }}</td>
                                        <td>@{{ item.lastname }}</td>
                                        <td>@{{ item.email }}</td>
                                        <td>@{{ item.prefered_language }}</td>
                                        <td>
                                            <label class="switch switch-3d switch-success">
                                                <input type="checkbox" class="switch-input" v-model="collection[index].newsletter" @change="toggleSwitch(item.resource_url, 'newsletter', collection[index])">
                                                <span class="switch-slider"></span>
                                            </label>
                                        </td>


                                        <td v-if="item.deleted_at===null">
                                            <div class="row no-gutters">
                                                <div class="col-auto">
                                                    <a class="btn btn-sm btn-spinner btn-info" :href="item.resource_url + '/edit'" title="{{ trans('brackets/admin-ui::admin.btn.edit') }}" role="button"><i class="fa fa-edit"></i></a>
                                                </div>
                                                <form class="col" @submit.prevent="deleteItem(item.resource_url)">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.delete') }}"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                        <td v-else>
                                            <div class="row no-gutters">
                                                <div class="col-auto">
                                                    <a class="btn btn-sm btn-spinner btn-warning" :href="item.resource_url + '/restore'" title="{{ trans('brackets/admin-ui::admin.btn.restore') }}" role="button"><i class="fa fa-recycle"></i></a>
                                                </div>
                                                <form class="col" @submit.prevent="deleteItem(item.resource_url+ '/purge')">
                                                    <button type="submit" class="btn btn-sm btn-danger" title="{{ trans('brackets/admin-ui::admin.btn.purge') }}"><i class="fa fa-trash-o"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <div class="row" v-if="pagination.state.total > 0">
                                <div class="col-sm">
                                    <span class="pagination-caption">{{ trans('brackets/admin-ui::admin.pagination.overview') }}</span>
                                </div>
                                <div class="col-sm-auto">
                                    <pagination></pagination>
                                </div>
                            </div>

                            <div class="no-items-found" v-if="!collection.length > 0">
                                <i class="icon-magnifier"></i>
                                <h3>{{ trans('brackets/admin-ui::admin.index.no_items') }}</h3>
                                <p>{{ trans('brackets/admin-ui::admin.index.try_changing_items') }}</p>
                                <a class="btn btn-primary btn-spinner" href="{{ url('admin/contacts/create') }}" role="button"><i class="fa fa-plus"></i>&nbsp; {{ trans('admin.contact.actions.create') }}</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </contact-listing>

@endsection
