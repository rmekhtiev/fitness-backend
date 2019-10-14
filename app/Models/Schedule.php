<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class Schedule extends RecurringEventModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'schedule_id';

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with, from a relevant controller
     * If left null, then $itemWith will be used
     */
    public static $collectionWith = null;

    /**
     * @var null|BaseTransformer The transformer to use for this model, if overriding the default
     */
    public static $transformer = null;

    /**
     * @var array The attributes that are mass assignable.
     */
    protected $fillable = [
        'start_date',
        'end_date',

        'recurrence_type',
        'recurrence_until',

        'schedulable_id',
        'schedulable_type',

        'trainer_id',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     */
    public function getValidationRules()
    {
        return [];
    }

    /**
     * Get the owning schedulable model.
     */
    public function schedulable()
    {
        return $this->morphTo();
    }

    public function trainer()
    {
        return $this->belongsTo(Trainer::class, 'trainer_id', 'trainer_id');
    }
}
