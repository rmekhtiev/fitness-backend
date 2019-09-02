<?php

namespace App\Models\Policies;

use App\Models\User;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;

class EmployeePolicy extends BasePolicy
{
    /**
     * Determine whether the user can create Employee.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user can view the Employee.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function view(User $user, Employee $employee)
    {
        return $user->isOwner() || $this->own($user, $employee);
    }

    /**
     * Determine whether the user can view the collection of Employee.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAll(User $user)
    {
        return $user->isOwner() || $user->isHallAdmin();
    }

    /**
     * Determine whether the user can update the Employee.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function update(User $user, Employee $employee)
    {
        return $this->own($user, $employee);
    }

    /**
     * Determine whether the user can delete the Employee.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function delete(User $user, Employee $employee)
    {
        return $user->isOwner();
    }

    /**
     * Determine whether the user owns the Employee.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Employee  $employee
     * @return mixed
     */
    public function own(User $user, Employee $employee) {
        // @todo
        return $user->isHallAdmin() && !empty($user->associatedEmployee) && $user->associatedEmployee->hall_id == $employee->hall_id;
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
            return $query->where('hall_id', $user->associatedEmployee->hall_id);
        });
    }
}
