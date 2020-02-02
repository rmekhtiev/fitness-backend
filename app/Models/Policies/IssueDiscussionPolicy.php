<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\IssueDiscussion;
use Illuminate\Database\Eloquent\Builder;

class IssueDiscussionPolicy extends BasePolicy
{
    /**
     * Determine whether the user can create IssueDiscussion.
     *
     * @param User $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can view the IssueDiscussion.
     *
     * @param User $user
     * @param IssueDiscussion $issueDiscussion
     * @return mixed
     */
    public function view(User $user, IssueDiscussion $issueDiscussion)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can view the collection of IssueDiscussion.
     *
     * @param User $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can update the IssueDiscussion.
     *
     * @param User $user
     * @param IssueDiscussion $issueDiscussion
     * @return mixed
     */
    public function update(User $user, IssueDiscussion $issueDiscussion)
    {
        return $user->isHallAdmin() || $user->isOwner();
    }

    /**
     * Determine whether the user can delete the IssueDiscussion.
     *
     * @param User $user
     * @param IssueDiscussion $issueDiscussion
     * @return mixed
     */
    public function delete(User $user, IssueDiscussion $issueDiscussion)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user owns the IssueDiscussion.
     *
     * @param User $user
     * @param IssueDiscussion $issueDiscussion
     * @return mixed
     */
    public function own(User $user, IssueDiscussion $issueDiscussion)
    {
        return $user->isHallAdmin();
    }

    /**
     * This function can be used to add conditions to the query builder,
     * which will specify the user's ownership of the model for the get collection query of this model
     *
     * @param User $user A user object against which to construct the query.
     *                   By default, the currently logged in user is used.
     * @param Builder $query
     * @return Builder|null
     */
    public function qualifyCollectionQueryWithUser(User $user, $query)
    {
        return $query;
    }
}
