<?php namespace Awebsome\Serverpilot\Models;

use Log;

use Model;
use Flash;
use ValidationException;

use Awebsome\Serverpilot\Classes\ServerPilot;

/**
 * Server Model
 */
class Server extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'awebsome_serverpilot_servers';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = ['available_runtimes'];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'sysusers'      => ['Awebsome\Serverpilot\Models\Sysuser','key' => 'server_api_id','otherKey' => 'api_id'],
        'databases'     => ['Awebsome\Serverpilot\Models\Database','key' => 'server_api_id','otherKey' => 'api_id'],
        'apps'          => ['Awebsome\Serverpilot\Models\App','key' => 'server_api_id','otherKey' => 'api_id']
    ];
    public $belongsTo = [
        'account' => 'Awebsome\ServerPilot\Models\Account'
    ];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

}
