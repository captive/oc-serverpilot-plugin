<?php namespace Awebsome\Serverpilot;

use Event;
use Backend;
use Redirect;

use Awebsome\Serverpilot\Models\Sync;
use Awebsome\Serverpilot\Models\Settings as CFG;

use Awebsome\Serverpilot\Classes\ServerPilot;

use System\Classes\PluginBase;
use Db;

class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'October.Drivers'
    ];

    public function boot()
    {

    }

    public function registerSchedule($schedule)
    {

    }

    public function registerSettings()
    {

        return [

            //Connection Settings
            'serverpilot'  => [
                'label'       => 'ServerPilot',
                'description' => 'Settings of ServerPilot',
                'category'    => 'ServerPilot',
                'icon'        => 'icon-cloud',
                'class'       => 'Awebsome\Serverpilot\Models\Settings',
                'order'       => 100,
                'permissions' => [ 'awebsome.serverpilot.settings' ],
                'keywords'    => 'Server ServerPilot'
            ],
        ];
    }
}
