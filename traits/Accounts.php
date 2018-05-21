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
                $this->apiPull('awebsome_serverpilot_servers', Server::parseData($this, $server));
            }
        }
    }


    /**
     * importUpdate()
     * ===========================================
     * @param array $server data.
     */
    public function apiPull($table, $resource)
    {
        $exists = Db::table($table)
            ->where('api_id', $resource['api_id'])
            ->first();

        if($exists)
        {
            Db::table($table)
            ->where('api_id', $resource['api_id'])
            ->update($resource);
        }else {
            Db::table($table)
            ->insert($resource);
        }
    }
}
