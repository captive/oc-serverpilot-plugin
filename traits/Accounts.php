<?php namespace Awebsome\ServerPilot\Traits;

use Awebsome\ServerPilot\Classes\ServerPilot;
use Awebsome\ServerPilot\Models\Server;

/**
 * ServerTrait
 */
trait Accounts
{
    public $api_data;

    /*
    'api_id'                => ['id'],
    'name'                  => ['name'],
    'autoupdates'           => ['autoupdates'],
    'firewall'              => ['firewall'],
    'lastaddress'           => ['lastaddress'],
    'datecreated'           => ['datecreated'],
    'lastconn'              => ['lastconn'],
    'created_at'            => ['datecreated'],
    'deny_unknown_domains'  => ['deny_unknown_domains'],
    'available_runtimes'    => ['available_runtimes']
    */


    /**
     * API Auth
     */
    public function api()
    {
        return ServerPilot::auth($this->client_id, $this->api_key);
    }

    /**
     * is_auth
     * ==============================================
     * Agregar atributo comprobar autenticación.
     */
    public function getIsAuthAttribute()
    {
        return $this->api()->isAuth();
    }

    /**
     * Import data from Account
     */
    public function import()
    {
        // Import all Servers of this account.
        $servers = $this->api()->servers()->get()->data;

        foreach($servers as $server)
        {
            $srv = Server::where('api_id', $server->id)->first();

            if(!$srv)
                $srv = new Server;
            else $srv = Server::find($srv->id);

            $srv->account_id            = $this->id;
            $srv->api_id                = $server->id;
            $srv->name                  = $server->name;
            $srv->autoupdates           = $server->autoupdates;
            $srv->firewall              = $server->firewall;
            $srv->lastaddress           = $server->lastaddress;
            $srv->datecreated           = $server->datecreated;
            $srv->lastconn              = $server->lastconn;
            $srv->created_at            = $server->datecreated;
            $srv->deny_unknown_domains  = $server->deny_unknown_domains;
            $srv->available_runtimes    = $server->available_runtimes;
            $srv->save();
        }

        return $servers;
    }
}
