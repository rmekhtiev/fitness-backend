<?php

namespace App\Models\Pivot;

use App\Models\Activity;
use App\Models\BaseModel;
use App\Models\Client;
use App\Models\Group;
use Illuminate\Database\Eloquent\Relations\Concerns\AsPivot;
use Spatie\Activitylog\Traits\LogsActivity;

class ClientGroup extends BaseModel
{
    use AsPivot;
    use LogsActivity;

    /**
     * @var string UUID key of the resource
     */
    public $primaryKey = 'client_group_id';

    protected static $logName = 'events';

    protected static $recordEvents = [
        'created',
        'deleted',
    ];

    public function tapActivity(Activity $activity, string $eventName)
    {
        \Log::debug($activity);

        $activity->hall_id = $this->group->hall_id;

        $activity->properties = [
            'group_id' => $this->group_id,
            'client_id' => $this->client_id,
        ];
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id');
    }

}
