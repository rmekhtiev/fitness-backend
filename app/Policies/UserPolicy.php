<?php

namespace App\Models\Policies;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class UserPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create User.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        //@todo
        return true;
    }

    /**
     * Determine whether the user can view the User.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function view(User $user, User $model)
    {
        return $this->own($user, $model);
    }

    /**
     * Determine whether the user can view the collection of User.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        // @todo
        return true;
    }

    /**
     * Determine whether the user can update the User.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function update(User $user, User $model)
    {
        return $this->own($user, $model);
    }

    /**
     * Determine whether the user can delete the User.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function delete(User $user, User $model)
    {
        return $this->own($user, $model);
    }

    /**
     * Determine whether the user owns the User.
     *
     * @param User $user
     * @param User $model
     * @return mixed
     */
    public function own(User $user, User $model)
    {
        // @todo
        return true;
    }

    /**
     * This function can be used to add conditions to the query builder,
     * which will specify the user's ownership of the model for the get collection query of this model
     *
     * @param User $user A user object against which to construct the query.
     * @param Builder $query
     * @return Builder|null
     */
    public function qualifyCollectionQueryWithUser(User $user, $query)
    {
        return $query->when(
            $user->isHallAdmin() && !empty($user->associatedEmployee),
            function (Builder $query) use ($user) {
                return $query->whereHas('associatedEmployee', function (Builder $query) use ($user) {
                    return $query->where('hall_id', $user->associatedEmployee->hall_id);
                });
            }
        );
    }
}
