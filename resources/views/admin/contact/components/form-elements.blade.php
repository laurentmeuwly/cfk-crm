
<div class="form-group row align-items-center" :class="{'has-danger': errors.has('title_id'), 'has-success': this.fields.title_id && this.fields.title_id.valid }">
    <label for="title_id" class="col-form-label text-md-right col-md-2">{{ trans('admin.contact.columns.title_id') }} *</label>
        <div class="col-md-9 col-xl-8">
            <multiselect
                v-model="form.title"
                v-validate="'required'"
                :options="titles"
                :multiple="false"
                track-by="id"
                label="name"
                tag-placeholder="{{ __('Select Title') }}"
                placeholder="{{ __('Title') }}">
            </multiselect>
        <div v-if="errors.has('title_id')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('title_id') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('firstname'), 'has-success': fields.firstname && fields.firstname.valid }">
    <label for="firstname" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.contact.columns.firstname') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.firstname" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('firstname'), 'form-control-success': fields.firstname && fields.firstname.valid}" id="firstname" name="firstname" placeholder="{{ trans('admin.contact.columns.firstname') }}">
        <div v-if="errors.has('firstname')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('firstname') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('lastname'), 'has-success': fields.lastname && fields.lastname.valid }">
    <label for="lastname" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.contact.columns.lastname') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.lastname" v-validate="'required'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('lastname'), 'form-control-success': fields.lastname && fields.lastname.valid}" id="lastname" name="lastname" placeholder="{{ trans('admin.contact.columns.lastname') }}">
        <div v-if="errors.has('lastname')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('lastname') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('email'), 'has-success': fields.email && fields.email.valid }">
    <label for="email" class="col-form-label text-md-right" :class="isFormLocalized ? 'col-md-4' : 'col-md-2'">{{ trans('admin.contact.columns.email') }}</label>
        <div :class="isFormLocalized ? 'col-md-4' : 'col-md-9 col-xl-8'">
        <input type="text" v-model="form.email" v-validate="'required|email'" @input="validate($event)" class="form-control" :class="{'form-control-danger': errors.has('email'), 'form-control-success': fields.email && fields.email.valid}" id="email" name="email" placeholder="{{ trans('admin.contact.columns.email') }}">
        <div v-if="errors.has('email')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('email') }}</div>
    </div>
</div>

<div class="form-group row align-items-center" :class="{'has-danger': errors.has('prefered_language'), 'has-success': this.fields.prefered_language && this.fields.prefered_language.valid }">
    <label for="prefered_language" class="col-form-label text-md-right col-md-2">{{ trans('admin.contact.columns.prefered_language') }}</label>
        <div class="col-md-9 col-xl-8">
            <multiselect v-model="form.prefered_language" 
                placeholder="{{ trans('brackets/admin-ui::admin.forms.select_an_option') }}" :options="{{ $languages }}" 
                open-direction="bottom">
            </multiselect>
        <div v-if="errors.has('prefered_language')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('prefered_language') }}</div>
    </div>
</div>

<div class="form-check row" :class="{'has-danger': errors.has('newsletter'), 'has-success': fields.newsletter && fields.newsletter.valid }">
    <div class="ml-md-auto" :class="isFormLocalized ? 'col-md-8' : 'col-md-10'">
        <input class="form-check-input" id="newsletter" type="checkbox" v-model="form.newsletter" v-validate="''" data-vv-name="newsletter"  name="newsletter_fake_element">
        <label class="form-check-label" for="newsletter">
            {{ trans('admin.contact.columns.newsletter') }}
        </label>
        <input type="hidden" name="newsletter" :value="form.newsletter">
        <div v-if="errors.has('newsletter')" class="form-control-feedback form-text" v-cloak>@{{ errors.first('newsletter') }}</div>
    </div>
</div>


