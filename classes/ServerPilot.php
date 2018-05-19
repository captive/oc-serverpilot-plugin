<?php namespace Awebsome\Serverpilot\Classes;

use Event;

use Awebsome\Serverpilot\Classes\Api;

class ServerPilot extends Api
{
    // constants
    const SP_CACHE              = 3600;
    const SP_TIMEOUT            = 30;
    const SP_API_ENDPOINT       = 'https://api.serverpilot.io/v1';
    const SP_USERAGENT          = 'serverpilot-api-php';
    const SP_HTTP_METHOD_POST   = 'POST';
    const SP_HTTP_METHOD_GET    = 'GET';
    const SP_HTTP_METHOD_DELETE    = 'DELETE';


    /**  Location for overloaded data. **/
    public $data;            # data for create, update, or delete.
    public $endpoint;        # resource to /endpoint
    public $ssl;             # add ssl to endpoint
    public $name;            # $resource name. ex: apps, servers, dbs

    public $model;           # Models of resources.
    public $table;           # Models of resources.
    public $id;              # Model of resource.

    public static function auth($client_id, $api_key)
    {
        $self = new Self;

        $self->client_id = $client_id;
        $self->api_key   = $api_key;

        return $self;
    }

    /**
     * Register Models.
     */
    public function registerModels()
    {
        return [
            'servers'   => 'Awebsome\ServerPilot\Models\Server',
            'sysusers'  => 'Awebsome\ServerPilot\Models\Sysuser',
            'apps'      => 'Awebsome\ServerPilot\Models\App',
            'dbs'       => 'Awebsome\ServerPilot\Models\Database',
            'actions'   => 'Awebsome\ServerPilot\Models\Action',
        ];
    }

    /**
     * Register tables.
     * ==========================================
     * mapped data between api and data tables.
     */
    public function registerTables()
    {
        return [
            'servers' => [
                # 'table_col'           => [api_key, mutatorMethod]
                'api_id'                => ['id'],
                'name'                  => ['name'],
                'autoupdates'           => ['autoupdates'],
                'firewall'              => ['firewall'],
                'lastaddress'           => ['lastaddress'],
                'datecreated'           => ['datecreated'],
                'lastconn'              => ['lastconn'],
                'created_at'            => ['datecreated'],
                'deny_unknown_domains'  => ['deny_unknown_domains']
            ],
            'sysusers' => [
                'api_id'                => ['id'],
                'server_api_id'         => ['serverid'],
                'name'                  => ['name']
            ],

            'dbs' => [
                'api_id'            => ['id'],
                'app_api_id'        => ['appid'],
                'server_api_id'     => ['serverid'],
                'name'              => ['name'],
                'user'              => ['user']
            ],

            'apps' => [
                'api_id'            => ['id'],
                'sysuser_api_id'    => ['sysuserid'],
                'server_api_id'     => ['serverid'],
                'name'              => ['name'],
                'runtime'           => ['runtime'],
                'ssl'               => ['ssl'],
                'autossl'           => ['autossl'],
                'domains'           => ['domains', 'setDomains'], //method to proccess data.
                'datecreated'       => ['datecreated'],
                ############## customs ##############
                'available_ssl'     => ['autossl', 'setAvailableSSL'],
                'auto_ssl'          => ['ssl', 'setAutoSSL'],
                'force_ssl'         => ['ssl', 'setForceSSL']
            ],

            'actions' => [
                'api_id'            => ['id'],
                'server_api_id'     => ['serverid'],
                'status'            => ['status'],
                'datecreated'       => ['datecreated']
            ]
        ];
    }

    public function getModel($resource)
    {
        $models = $this->registerModels();
        return $models[$resource];
    }

    public function getTable($resource)
    {
        $tables = $this->registerTables();
        return $tables[$resource];
    }

    /**
     * isAuth
     * ==============================
     * check authentication
     * @return boolean is auth
     */
    public function isAuth()
    {
        $servers = $this->servers()->get();
        $error = @$servers->error->code;

        if($error == 401)
            return false;
        return true;
    }


    /**
     * Servers
     * ==============================
     * Resources Methods ServerPilot
     * @param $id to retrive one.
     * @return array data endpoint resource id
    */
    public function servers($id = null)
    {
        $this->name = __FUNCTION__;
        $this->model = $this->getModel($this->name);
        $this->table = $this->getTable($this->name);
        $this->id = $id;

        return $this;
    }

    public function apps($id = null)
    {
        $this->name = __FUNCTION__;
        $this->model = $this->getModel($this->name);
        $this->table = $this->getTable($this->name);
        $this->id = $id;

        return $this;
    }

    public function sysusers($id = null)
    {
        $this->name = __FUNCTION__;
        $this->model = $this->getModel($this->name);
        $this->table = $this->getTable($this->name);
        $this->id = $id;

        return $this;
    }

    public function dbs($id = null)
    {

        $this->name = __FUNCTION__;
        $this->model = $this->getModel($this->name);
        $this->table = $this->getTable($this->name);
        $this->id = $id;

        return $this;
    }

    public function actions($id)
    {
        $this->name = __FUNCTION__;
        $this->model = $this->getModel($this->name);
        $this->table = $this->getTable($this->name);
        $this->id = $id;

        return $this;
    }


    /**
     * Helper Methods
     */

    public function import($mode = null)
    {
        if(!$mode)
        {
            if(!$this->id)
                $import = Import::all($this);
            else
                $import = Import::import($this, $this->get()->data);
        }else if($mode == 'oneToOne')
        {
            $import = Import::allOneToOne($this);
        }

        return $import;
    }

    public function importBatch()
    {
        if(!$this->id)
        {
            $import = Import::batch($this);
            return $import;
        }
    }

    /**
     * CRUD METHODS
     */

    /**
     * get resource
     * ========================================================
     * get endpoint response.
     */
    public function get()
    {
        if(!$this->endpoint)
            $this->endpoint = ($this->id) ? $this->name . '/'.$this->id : $this->name;

        return $this->request($this->endpoint, $this->data);
    }

    public function forceSSL($force)
    {
        $this->endpoint = $this->name.'/'.$this->id.'/ssl';

        if($force)
            $val = ['force' => true];
        else $val = ['force' => false];

        return $this->request($this->endpoint, $val, self::SP_HTTP_METHOD_POST);
    }

    public function autoSSL($auto)
    {
        $this->endpoint = $this->name.'/'.$this->id.'/ssl';

        if($auto)
            $val = ['auto' => true];

        if($auto == true)
            return $this->request($this->endpoint, $val, self::SP_HTTP_METHOD_POST);
        else
            return $this->request($this->endpoint, null, self::SP_HTTP_METHOD_DELETE);
    }

    public function update($data)
    {
        if($this->id && !$this->endpoint)
            $this->endpoint = $this->name . '/'.$this->id;

        return $this->request($this->endpoint, $data, self::SP_HTTP_METHOD_POST);
    }

    public function create($data)
    {
        return $this->request($this->name, $data, self::SP_HTTP_METHOD_POST);
    }

    public function delete()
    {
        $this->endpoint = $this->name . '/'.$this->id;

        $this->request($this->endpoint, null, self::SP_HTTP_METHOD_DELETE);
    }

    // Plus conditions & restrinctions.

    /*public function plus()
    {

        // Extend all backend form usage
        Event::listen('backend.form.extendFields', function($widget) {

            // Only for the User controller
            if (!$widget->getController() instanceof \Awebsome\Serverpilot\Controllers\Apps) {
                return;
            }

            // Only for the User model
            if (!$widget->model instanceof \Awebsome\Serverpilot\Models\App) {
                return;
            }

            if(!class_exists('Awebsome\Serverpilotplus\Classes\BackupHandler'))
                $widget->removeField('backups');
        });
    }*/
}
