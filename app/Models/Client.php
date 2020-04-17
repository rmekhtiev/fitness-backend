<?php

namespace App\Models;

use App\Enums\ClientStatus;
use App\Enums\Gender;
use App\Enums\QuestionnaireStatus;
use App\Models\Pivot\ClientGroup;
use App\Transformers\BaseTransformer;
use BenSampo\Enum\Rules\EnumValue;
use Carbon\Carbon;
use Fico7489\Laravel\EloquentJoin\Traits\EloquentJoin;
use Iatstuti\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Validation\Rule;
use QrCode;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\QueryBuilder\AllowedFilter;

class Client extends BaseModel
{
    use Notifiable;
    use EloquentJoin;
    use LogsActivity;
    use SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['visitHistoryRecords', 'lastVisitHistoryRecord', 'sessions'];

    protected $dates = ['deleted_at'];

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'client_id';

    protected $perPage = 15;

    /**
     * @var null|array What relations should one model of this entity be returned with, from a relevant controller
     */
    public static $itemWith = [
        'activeSubscriptions',
        'inactiveSubscriptions',
        'groups',
        'identifiers',
        'visitHistoryRecords',
        'lastVisitHistoryRecord',
    ];

    /**
     * @var null|array What relations should a collection of models of this entity be returned with,
     * from a relevant controller
     *
     * If left null, then $itemWith will be used
     */
    public static $collectionWith = [
        'activeSubscriptions',
        'lastVisitHistoryRecord',
        'visitHistoryRecords',
        'inactiveSubscriptions',
        'identifiers',
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
        'birth_date',
        'phone_number',
        'instagram',
        'whats_app_number',
        'questionnaire_status',
        'avatar',
        'comment',

        'primary_hall_id',
    ];

    /**
     * @var array The attributes that should be hidden for arrays and API output
     */
    protected $hidden = [];

    protected $appends = [
        'name',
        'full_name',
        'status',
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
            'whats_app_number' => [
                'sometimes',
                'nullable',
                Rule::unique('clients', 'whats_app_number')->ignoreModel($this),
            ],

            'gender' => [
                'sometimes',
                'nullable',
                new EnumValue(Gender::class, true),
            ],

            'primary_hall_id' => 'required|uuid|exists:halls,hall_id',
            'questionnaire_status' => ['required', new EnumValue(QuestionnaireStatus::class)],
            'comment' => 'max:255',
            //'birth_date' => 'required',
        ];
    }

    public static function getAllowedFilters()
    {
        return [
            AllowedFilter::exact('id', 'clients.client_id'),
            AllowedFilter::exact('client_id', 'clients.client_id'),
            AllowedFilter::exact('primary_hall_id', 'clients.primary_hall_id'),
            AllowedFilter::scope('search'),
            AllowedFilter::scope('status'),
            AllowedFilter::scope('subscriable'),
            AllowedFilter::scope('birthday'),
        ];
    }


    public function scopeSearch(Builder $query, $search)
    {
        return $query->where('first_name', 'ILIKE', "%{$search}%")
            ->orWhere('middle_name', 'ILIKE', "%{$search}%")
            ->orWhere('last_name', 'ILIKE', "%{$search}%")
            ->orWhere('phone_number', 'ILIKE', "%{$search}%")
            ->orWhere('instagram', 'ILIKE', "%{$search}%")
            ->orWhere('whats_app_number', 'ILIKE', "%{$search}%");
    }

    public function scopeSubscriable(Builder $query, $search)
    {
        return $query->whereHas('activeSubscriptions', function (Builder $builder) use ($search) {
            return $builder->subscriable($search);
        });
    }

    public function scopeStatus(Builder $query, $status)
    {
        $query->when($status === ClientStatus::FROZEN, function (Builder $builder) {
            $builder->whereHas('activeSubscriptions', function (Builder $builder) {
                return $builder->frozen();
            });
        })->when($status === ClientStatus::ACTIVE, function (Builder $builder) {
            $builder->whereHas('activeSubscriptions', function (Builder $builder) {
                return $builder->active();
            })->whereDoesntHave('activeSubscriptions', function (Builder $builder) {
                return $builder->frozen();
            });
        })->when($status === ClientStatus::NOT_ACTIVATED, function (Builder $builder) {
            $builder->whereHas('inactiveSubscriptions', function (Builder $builder) {
                return $builder->inactive();
            })->whereDoesntHave('activeSubscriptions');
        })->when($status === ClientStatus::EXPIRED, function (Builder $builder) {
            $builder->whereHas('subscriptions', function (Builder $builder) {
                return $builder->expired();
            })->whereDoesntHave('activeSubscriptions');
        })->when($status === ClientStatus::NO_SUBSCRIPTION, function (Builder $builder) {
            $builder->whereDoesntHave('subscriptions', function (Builder $builder) {
                return $builder;
            });
        });
    }

    public function scopeBirthday(Builder $query, $value)
    {
        if ($value){
            return $query->whereDay('birth_date', '<=', today())
                ->whereDay('birth_date', '>=', today()->addDays(-7))
                ->whereMonth('birth_date', today());
        }
    }


    public function primaryHall()
    {
        return $this->belongsTo(Hall::class, 'primary_hall_id');
    }

    public function visitHistoryRecords()
    {
        return $this->hasMany(VisitHistoryRecord::class, 'client_id');
    }

    public function lastVisitHistoryRecord()
    {
        return $this->hasOne(VisitHistoryRecord::class, 'client_id');
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'client_id', 'client_id');
    }

    public function identifiers()
    {
        return $this->hasMany(Identifier::class, 'client_id', 'client_id');
    }

    public function activeSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'client_id', 'client_id')
            ->whereDate('issue_date', '<=', today())
            ->whereDate('valid_till', '>=', today());
    }

    public function inactiveSubscriptions()
    {
        return $this->hasMany(Subscription::class, 'client_id', 'client_id')
            ->whereDate('issue_date', '>', today())->orderBy('issue_date');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'client_group', 'client_id', 'group_id')
            ->using(ClientGroup::class)
            ->withTimestamps();
    }

    public function sessions()
    {
        return $this->belongsTo(TrainingSession::class, 'client_id', 'client_id');
    }

    public function getFirstNameAttribute()
    {
        return mb_convert_case($this->attributes['first_name'], MB_CASE_TITLE);
    }

    public function getMiddleNameAttribute()
    {
        return mb_convert_case($this->attributes['middle_name'], MB_CASE_TITLE);
    }

    public function getLastNameAttribute()
    {
        return mb_convert_case($this->attributes['last_name'], MB_CASE_TITLE);
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

    public function getStatusAttribute()
    {
        if ($this->activeSubscriptions()->value('frozen_till') >= (today()->modify('-1 day'))) {
            return ClientStatus::FROZEN;
        } elseif ($this->activeSubscriptions()->value('valid_till') >= today()
            && $this->activeSubscriptions()->value('issue_date') <= today()) {
            return ClientStatus::ACTIVE;
        } elseif ($this->inactiveSubscriptions()->value('issue_date') > today()) {
            return ClientStatus::NOT_ACTIVATED;
        } elseif ($this->subscriptions()->count() > 0) {
            return ClientStatus::EXPIRED;
        }
        return ClientStatus::NO_SUBSCRIPTION;
    }

    public function getAvatarAttribute() {
        return $this->attributes['avatar'] ? asset($this->attributes['avatar']) : null;
    }
}
