<?php namespace Awebsome\Serverpilot\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Accounts Back-end Controller
 */
class Accounts extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['awebsome.serverpilot.accounts'];
    
    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Awebsome.Serverpilot', 'serverpilot', 'accounts');
    }
}
