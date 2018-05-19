<?php namespace Awebsome\ServerPilot\Models;

use Model;
use Awebsome\ServerPilot\Classes\ServerPilot;

/**
 * Account Model
 */
class Account extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'awebsome_serverpilot_accounts';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    use \October\Rain\Database\Traits\Validation;

    /**
     * @var array Validation rules
     */
    protected $rules = [
        'email' => 'required|email',
        'client_id' => 'required',
        'api_key' => 'required',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    public function api()
    {
        return ServerPilot::auth($this->client_id, $this->api_key);
    }
    public function getIsAuthAttribute()
    {
        return $this->api()->isAuth();
    }
}
