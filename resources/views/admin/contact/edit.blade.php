@extends('brackets/admin-ui::admin.layout.default')

@section('title', trans('admin.contact.actions.edit', ['name' => $contact->email]))

@section('body')

    <div class="container-xl">
        <div class="card">

            <contact-form
                :action="'{{ $contact->resource_url }}'"
                :data="{{ $contact->toJson() }}"
                :titles="{{ $titles->toJson() }}"
                :sources="{{ $sources->toJson() }}"
                :available-categories="{{ $categories->toJson() }}"
                v-cloak
                inline-template>
            
                <form class="form-horizontal form-edit" method="post" @submit.prevent="onSubmit" :action="action" novalidate>


                    <div class="card-header">
                        <i class="fa fa-pencil"></i> {{ trans('admin.contact.actions.edit', ['name' => $contact->email]) }}
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col">
                                @include('admin.contact.components.form-elements')
                            </div>
                            <div class="col-md-12 col-lg-12 col-xl-5 col-xxl-4">
                                @include('admin.contact.components.form-elements-right')
                            </div>
                    </div>
                    
                    
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary" :disabled="submiting">
                            <i class="fa" :class="submiting ? 'fa-spinner' : 'fa-download'"></i>
                            {{ trans('brackets/admin-ui::admin.btn.save') }}
                        </button>
                    </div>
                    
                </form>

        </contact-form>

        </div>
    
</div>

@endsection