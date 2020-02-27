<?php

namespace App\Models;

class Role extends BaseModel
{
    /**
     * Role constants
     */
    public const ROLE_OWNER = 'owner';
    public const ROLE_HALL_ADMIN = 'hall_admin';
    public const ROLE_CONCIERGE = 'concierge';

    /**
     * @var string Auto increments integer key
     */
    public $primaryKey = 'role_id';

    /**
     * @var string UUID key
     */
    public $uuidKey = 'role_uuid';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description',
    ];
}
