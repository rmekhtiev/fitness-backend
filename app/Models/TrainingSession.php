<?php

namespace App\Models;

use App\Transformers\BaseTransformer;

class TrainingSession extends BaseModel
{
    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'training_session_id';

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
        'client_id',
        'trainer_id',
        'payed_until',
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
        return [
            'client_id' => 'required|nullable|uuid|exists:clients,client_id',
            'trainer_id' => 'required|nullable|uuid|exists:trainers,trainer_id',
            'payed_until' => 'required', // todo
        ];
    }

    public function client()
    {
        return $this->hasOne(Client::class, 'client_id', 'client_id');
    }

    public function trainer()
    {
        return $this->hasOne(Trainer::class, 'trainer_id', 'trainer_id');
    }
}
