<?php

namespace Dewsign\NovaSpaces\Policies;

use Illuminate\Support\Facades\Gate;
use Illuminate\Auth\Access\HandlesAuthorization;

class SpacePolicy
{
    use HandlesAuthorization;

    public function viewAny()
    {
        return Gate::any(['viewSpace', 'manageSpace']);
    }

    public function view($model)
    {
        return Gate::any(['viewSpace', 'manageSpace'], $model);
    }

    public function create($user)
    {
        return $user->can('manageSpace');
    }

    public function update($user, $model)
    {
        return $user->can('manageSpace', $model);
    }

    public function delete($user, $model)
    {
        return $user->can('manageSpace', $model);
    }

    public function restore($user, $model)
    {
        return $user->can('manageSpace', $model);
    }

    public function forceDelete($user, $model)
    {
        return $user->can('manageSpace', $model);
    }
}
