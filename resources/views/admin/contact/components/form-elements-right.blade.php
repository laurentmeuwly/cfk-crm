<div class="card">
    <div class="card-header">
       <span><i class="fa icon-book-open"></i> {{ trans('admin.contact.columns.categories') }} </span>
    </div>

    <div class="card-block">
        <div class="form-group row align-items-center"
             :class="{'has-danger': errors.has('categories'), 'has-success': this.fields.categories && this.fields.categories.valid }">
            <label for="author_id"
                   class="col-form-label text-center col-md-4 col-lg-3">{{ trans('admin.contact.columns.categories') }}</label>
            <div class="col-md-8 col-lg-9">

                <multiselect
                        v-model="form.categories"
                        :options="availableCategories"
                        :multiple="true"
                        track-by="id"
                        label="name"
                        tag-placeholder="{{ __('Select Categories') }}"
                        placeholder="{{ __('Categorie') }}">
                </multiselect>

                <div v-if="errors.has('categories')" class="form-control-feedback form-text" v-cloak>@{{
                    errors.first('categories') }}
                </div>
            </div>
        </div>

    </div>
</div>

<div class="card">
    <div class="card-header">
        <i class="fa fa-check"></i> {{ trans('admin.contact.columns.source_id') }}</span>
    </div>

    <div class="card-block">
        <div class="form-group row align-items-center" :class="{'has-danger': errors.has('source_id'), 'has-success': this.fields.source_id && this.fields.source_id.valid }">
            <label for="source_id" class="col-form-label text-md-right col-md-2 col-lg-3">{{ trans('admin.contact.columns.source_id') }}</label>
            <div class="col-sm-8 col-lg-9">
                <multiselect
                    v-model="form.source"
                    :options="sources"
                    :multiple="false"
                    track-by="id"
                    label="name"
                    tag-placeholder="{{ __('Select Source') }}"
                    placeholder="{{ __('Source') }}">
                </multiselect>
                <div v-if="errors.has('source_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('source_id') }}</div>
            </div>
        </div>
    </div>
</div>