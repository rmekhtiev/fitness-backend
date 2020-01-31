<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\VisitHistoryRecord;

class VisitHistoryRecordPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create VisitHistoryRecord.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin() || $user->isConcierge();
    }

    /**
     * Determine whether the user can view the VisitHistoryRecord.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitHistoryRecord  $visitHistory
     * @return mixed
     */
    public function view(User $user, VisitHistoryRecord $visitHistory)
    {
        //todo
        return true;
    }

    /**
     * Determine whether the user can view the collection of VisitHistoryRecord.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        // @todo
        return true;
    }

    /**
     * Determine whether the user can update the VisitHistoryRecord.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitHistoryRecord  $visitHistory
     * @return mixed
     */
    public function update(User $user, VisitHistoryRecord $visitHistory)
    {
        return $this->own($user, $visitHistory);
    }

    /**
     * Determine whether the user can delete the VisitHistoryRecord.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitHistoryRecord  $visitHistory
     * @return mixed
     */
    public function delete(User $user, VisitHistoryRecord $visitHistory)
    {
        return $this->own($user, $visitHistory);
    }

    /**
     * Determine whether the user owns the VisitHistoryRecord.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\VisitHistoryRecord  $visitHistory
     * @return mixed
     */
    public function own(User $user, VisitHistoryRecord $visitHistory)
    {
        // @todo
        return true;
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
        return $query;
    }
}
