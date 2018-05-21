<?php namespace Awebsome\ServerPilot\Models;

use Model;
use ValidationException;

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
        'client_id' => 'required|unique:awebsome_serverpilot_accounts',
        'api_key' => 'required',
    ];

    /**
     * @var array Relations
     */
    public $hasOne = [];
    public $hasMany = [
        'servers' => ['Awebsome\ServerPilot\Models\Server']
    ];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    use \Awebsome\ServerPilot\Traits\Accounts;

    public function beforeSave()
    {
        if(!$this->is_auth)
            throw new ValidationException(['error_mesage' => trans('awebsome.serverpilot::lang.error.401')]);
        else {
            $this->updated_at = time();
        }
    }

    public function afterSave()
    {
        $this->import();
    }
}
