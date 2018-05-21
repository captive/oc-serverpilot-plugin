<?php namespace Awebsome\ServerPilot\Traits;

use Db;
use Awebsome\ServerPilot\Classes\ServerPilot;
use Awebsome\ServerPilot\Models\Server;

/**
 * ServerTrait
 */
trait Accounts
{

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

        if($servers)
        {
            foreach($servers as $server)
            {
                $this->apiPull([
                    'account_id'            => $this->id,
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
                ]);
            }
        }
    }

    /**
     * importUpdate()
     * ===========================================
     * @param array $server data.
     */
    public function apiPull($server)
    {
        $exists = Server::where('api_id', $server['api_id'])->first();

        if($exists)
        {
            Db::table('awebsome_serverpilot_servers')
            ->where('api_id', $server['api_id'])
            ->update($server);
        }else {
            Db::table('awebsome_serverpilot_servers')
            ->insert($server);
        }
    }
}
