<div class="sidebar">
    <nav class="sidebar-nav">
        <ul class="nav">
            <li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.content') }}</li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/contacts') }}"><i class="nav-icon fa fa-address-book"></i> {{ trans('admin.contact.title') }}</a></li>
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/categories') }}"><i class="nav-icon icon-book-open"></i> {{ trans('admin.category.title') }}</a></li>
            @can('admin.title')
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/titles') }}"><i class="nav-icon fa fa-tags"></i> {{ trans('admin.title.title') }}</a></li>
            @endcan
            @can('admin.source')
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/sources') }}"><i class="nav-icon icon-ghost"></i> {{ trans('admin.source.title') }}</a></li>
            @endcan
           {{-- Do not delete me :) I'm used for auto-generation menu items --}}

            <li class="nav-title">{{ trans('brackets/admin-ui::admin.sidebar.settings') }}</li>
            @can('admin.role')
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/roles') }}"><i class="nav-icon icon-graduation"></i> {{ trans('admin.role.title') }}</a></li>
            @endcan
            @can('admin.permission')
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/permissions') }}"><i class="nav-icon icon-drop"></i> {{ trans('admin.permission.title') }}</a></li>           
            @endcan
            @can('admin.permission')
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/admin-users') }}"><i class="nav-icon icon-user"></i> {{ __('Manage access') }}</a></li>
            @endcan
            @can('admin.translation.index')
            <li class="nav-item"><a class="nav-link" href="{{ url('admin/translations') }}"><i class="nav-icon icon-location-pin"></i> {{ __('Translations') }}</a></li>
            @endcan
            {{-- Do not delete me :) I'm also used for auto-generation menu items --}}
            {{--<li class="nav-item"><a class="nav-link" href="{{ url('admin/configuration') }}"><i class="nav-icon icon-settings"></i> {{ __('Configuration') }}</a></li>--}}
        </ul>
       
    </nav>

    <button class="sidebar-minimizer brand-minimizer" type="button"></button>
</div>
