<?php namespace Awebsome\ServerPilot\Traits;

use Awebsome\ServerPilot\Models\Account;
use Awebsome\ServerPilot\Classes\ServerPilot;

/**
 * ServerTrait
 */
trait Servers
{
    public $api_data;   # Retornar datos devueltos por la api.

    /**
     * API Auth
     */
    public function api()
    {
        return ServerPilot::auth($this->client_id, $this->api_key);
    }


}
