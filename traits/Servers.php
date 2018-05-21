<?php namespace Awebsome\ServerPilot\Traits;

use Awebsome\ServerPilot\Models\Account;
use Awebsome\ServerPilot\Classes\ServerPilot;

/**
 * ServerTrait
 */
trait Servers
{
    /**
     * API Auth
     */
    public function api()
    {
        return ServerPilot::auth($this->account->client_id, $this->account->api_key);
    }
    
    public static function parseData($account, $server)
    {
        return [
            'account_id'            => $account->id,
            'api_id'                => $server->id,
            'name'                  => $server->name,
            'autoupdates'           => $server->autoupdates,
            'firewall'              => $server->firewall,
            'lastaddress'           => $server->lastaddress,
            'datecreated'           => $server->datecreated,
            'lastconn'              => $server->lastconn,
            'created_at'            => $server->datecreated,
            'deny_unknown_domains'  => $server->deny_unknown_domains,
            'available_runtimes'    => json_encode($server->available_runtimes)
        ];
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
