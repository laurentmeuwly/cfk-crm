<?php

namespace App\Policies;

use App\Models\Contactform;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactformPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Contactform $contactform)
    {
        return true;
    }

    /**
     * Determine whether the user can view the related model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewTitle(User $user, Contactform $contactform)
    {
        return true;
    }

    /**
     * Determine whether the user can view the related model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewSource(User $user, Contactform $contactform)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Contactform $contactform)
    {
        return true;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Contactform $contactform)
    {
        return true;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Contactform $contactform)
    {
        return true;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Contactform  $contactform
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Contactform $contactform)
    {
        return true;
    }
}
