<?php

namespace Nikservik\Subscriptions\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Nikservik\Subscriptions\Models\Tariff;

class TariffPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->role >= User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tariff  $tariff
     * @return mixed
     */
    public function view(User $user, Tariff $tariff)
    {
        return $user->role >= User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->role >= User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tariff  $tariff
     * @return mixed
     */
    public function update(User $user, Tariff $tariff)
    {
        return $user->role >= User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tariff  $tariff
     * @return mixed
     */
    public function delete(User $user, Tariff $tariff)
    {
        return $user->role >= User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tariff  $tariff
     * @return mixed
     */
    public function restore(User $user, Tariff $tariff)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Tariff  $tariff
     * @return mixed
     */
    public function forceDelete(User $user, Tariff $tariff)
    {
        //
    }
}
