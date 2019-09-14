<?php

namespace App\Models;

use App\Enums\Gender;
use App\Transformers\BaseTransformer;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\QueryBuilder\AllowedFilter;

class Client extends BaseModel
{
    use Notifiable;
    use LogsActivity;

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'client_id';

    protected $perPage = null;

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
        'first_name',
        'middle_name',
        'last_name',
        'phone_number',
        'email',

        'primary_hall_id',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected $appends = [
        'name',
        'full_name',
    ];

    protected static $recordEvents = [
        'created'
    ];

    protected static $logName = 'events';

    public function tapActivity(Activity $activity, string $eventName)
    {
        $activity->hall_id = $this->primary_hall_id;
    }

    /**
     * Return the validation rules for this model
     *
     * @return array Rules
     * @todo: regexp for phone
     *
     */
    public function getValidationRules()
    {
        return [
            'first_name' => 'required',
            'middle_name' => 'sometimes|nullable',
            'last_name' => 'required',
            'phone_number' => [
                'required',
                Rule::unique('clients', 'phone_number')->ignoreModel($this),
            ],
            'email' => [
                'sometimes',
                'nullable',
                'email',
                Rule::unique('clients', 'email')->ignoreModel($this),
            ],

            'gender' => [
                'sometimes',
                'nullable',
                new EnumValue(Gender::class, true),
            ],

            'primary_hall_id' => 'sometimes|nullable|uuid|exists:halls,hall_id',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'client_id'),
            AllowedFilter::exact('client_id'),
            AllowedFilter::exact('primary_hall_id'),
            AllowedFilter::scope('search'),
        ];
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('first_name', 'ILIKE', "%{$search}%")
            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
            ->orWhere('last_name', 'ILIKE', "%{$search}%")
            ->orWhere('phone_number', 'ILIKE', "%{$search}%")
            ->orWhere('email', 'ILIKE', "%{$search}%");
    }

    public function primaryHall()
    {
        return $this->belongsTo(Hall::class, 'primary_hall_id');
    }

    /**
     * @return mixed|string
     */
    public function getNameAttribute()
    {
        // phpcs:ignore
        return $this->last_name ? $this->last_name . ($this->first_name ? (' ' . mb_substr($this->first_name, 0, 1) . '.') : '') . ($this->middle_name ? (' ' . mb_substr($this->middle_name, 0, 1) . '.') : '') : $this->first_name;
    }

    /**
     * @return mixed|string
     */
    public function getFullNameAttribute()
    {
        // phpcs:ignore
        return $this->last_name ? $this->last_name . ($this->first_name ? (' ' . $this->first_name) : '') . ($this->middle_name ? (' ' . $this->middle_name) : '') : $this->first_name;
    }

    public function setPhoneNumberAttribute($number)
    {
        $this->attributes['phone_number'] = preg_replace('/[^0-9,+]/', '', $number);
    }

    public function routeNotificationForSigmaSms()
    {
        return $this->phone_number;
    }

}
