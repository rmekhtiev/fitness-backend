<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Trainer;
use Illuminate\Database\Eloquent\Builder;

class TrainerPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Trainer.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can view the Trainer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trainer  $trainer
     * @return mixed
     */
    public function view(User $user, Trainer $trainer)
    {
        return true;
    }

    /**
     * Determine whether the user can view the collection of Trainer.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the Trainer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trainer  $trainer
     * @return mixed
     */
    public function update(User $user, Trainer $trainer)
    {
        return $user->isOwner() || $this->own($user, $trainer);
    }

    /**
     * Determine whether the user can delete the Trainer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trainer  $trainer
     * @return mixed
     */
    public function delete(User $user, Trainer $trainer)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user owns the Trainer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Trainer  $trainer
     * @return mixed
     */
    public function own(User $user, Trainer $trainer) {
        return $user->isHallAdmin() && !empty($user->associatedEmployee) && $user->associatedEmployee->hall_id == $trainer->hall_id;
    }

    /**
     * This function can be used to add conditions to the query builder,
     * which will specify the user's ownership of the model for the get collection query of this model
     *
     * @param \App\Models\User $user A user object against which to construct the query. By default, the currently logged in user is used.
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder|null
     */
    public function qualifyCollectionQueryWithUser(User $user, $query)
    {
        return $query->when($user->isHallAdmin() && !empty($user->associatedEmployee), function (Builder $query) use ($user) {
            return $query->whereHallId($user->associatedEmployee->hall_id);
        });
    }
}
