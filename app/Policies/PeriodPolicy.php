<?php

namespace App\Policies;

use App\Models\Period;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class PeriodPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('ver periodos');  
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Period $period): bool
    {
        return $user->can('ver periodos');  
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('crear periodos');  
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Period $period): bool
    {
        return $user->can('editar periodos');  
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Period $period): bool
    {
        return $user->can('eliminar periodos');  
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Period $period): bool
    {
        return true; 
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Period $period): bool
    {
        return true;
    }
}
