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
        return ServerPilot::auth($this->account->client_id, $this->account->api_key);
    }

    /**
     * Push UPDATE Server
     */
    public function apiPush()
    {
        $this->api()->servers($this->api_id)->update([
            'autoupdates'           => ($this->autoupdates) ? true:false,
            'firewall'              => ($this->firewall)    ? true:false,
            'deny_unknown_domains'  => ($this->deny_unknown_domains) ? true:false
        ]);
    }
}
