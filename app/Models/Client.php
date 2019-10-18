<?php

namespace App\Models;

use App\Enums\Gender;
use App\Models\Pivot\ClientGroup;
use App\Transformers\BaseTransformer;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;
use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Illuminate\Validation\Rule;
use QrCode;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\QueryBuilder\AllowedFilter;

class Client extends BaseModel
{
    use Notifiable;
    use EloquentJoin;
    use LogsActivity;

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'client_id';

    protected $perPage = 15;

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [
        'activeSubscription',
        'groups',
        'lastVisitHistoryRecord',
    ];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with, from a relevant controller
     * If left null, then $itemWith will be used
     */
    public static $collectionWith = [
        'activeSubscription',
        'lastVisitHistoryRecord',
    ];

    public static $itemWithCount = [
        'subscriptions'
    ];

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
        'instagram',
        'whats_up_number',

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

    public static function boot()
    {
        parent::boot();


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
            'instagram' => [
                'sometimes',
                'nullable',
                Rule::unique('clients', 'instagram')->ignoreModel($this),
            ],
            'whats_up_number' => [
                'sometimes',
                'nullable',
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
            AllowedFilter::exact('id', 'clients.client_id'),
            AllowedFilter::exact('client_id', 'clients.client_id'),
            AllowedFilter::exact('primary_hall_id', 'clients.primary_hall_id'),
            AllowedFilter::scope('search'),
        ];
    }

    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('first_name', 'ILIKE', "%{$search}%")
            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
            ->orWhere('last_name', 'ILIKE', "%{$search}%")
            ->orWhere('phone_number', 'ILIKE', "%{$search}%")
            ->orWhere('instagram', 'ILIKE', "%{$search}%")
            ->orWhere('what-up-number', 'ILIKE', "%{$search}%");
    }

    public function primaryHall()
    {
        return $this->belongsTo(Hall::class, 'primary_hall_id');
    }

    public function visitHistoryRecords()
    {
        return $this->hasMany( VisitHistoryRecord::class, 'client_id');
    }

    public function lastVisitHistoryRecord()
    {
        return $this->hasOne( VisitHistoryRecord::class, 'client_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'client_id', 'client_id');
    }

    public function activeSubscription()
    {
        return $this->hasOne(Subscription::class, 'client_id', 'client_id')
            ->whereDate('issue_date', '<=', today())
            ->whereDate('valid_till', '>=', today());
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'client_group', 'client_id', 'group_id')
            ->using(ClientGroup::class)
            ->withTimestamps();
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

    public function getQrCode($size = 200, $format = 'png')
    {
        return QrCode::format($format)->size($size)->generate(json_encode(['client_id' => $this->client_id]));
    }
}
