<?php namespace Awebsome\Serverpilot\Controllers;

use Backend;
use BackendMenu;

use Backend\Classes\Controller;

/**
 * Servers Back-end Controller
 */
class Servers extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['awebsome.serverpilot.servers'];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Awebsome.Serverpilot', 'serverpilot', 'servers');
    }

    public function index()
    {
        $this->asExtension('ListController')->index();
    }
}
