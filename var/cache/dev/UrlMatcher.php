<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/api' => [[['_route' => 'home_api', '_controller' => 'App\\Controller\\MachineOutilController::index'], null, ['GET' => 0], null, true, false, null]],
        '/api/machines' => [[['_route' => 'list_machines_collections_get', '_controller' => 'App\\Controller\\MachineOutilController::getAllMachines'], null, ['GET' => 0], null, false, false, null]],
        '/api/all_user_machines' => [[['_route' => 'user_list_machines_collections_get', '_controller' => 'App\\Controller\\MachineOutilController::getAllUserMachines'], null, ['GET' => 0], null, false, false, null]],
        '/api/create_machine' => [[['_route' => 'item_machine_create', '_controller' => 'App\\Controller\\MachineOutilController::createMachine'], null, ['POST' => 0], null, false, false, null]],
        '/api/login' => [[['_route' => 'api_login_check', '_controller' => 'App\\Controller\\AuthController::login'], null, ['POST' => 0], null, false, false, null]],
        '/api/register' => [[['_route' => 'register', '_controller' => 'App\\Controller\\AuthController::register'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
        0 => '{^(?'
                .'|/_error/(\\d+)(?:\\.([^/]++))?(*:35)'
                .'|/api/(?'
                    .'|machines/(\\d+)(*:64)'
                    .'|update_data_machine/(\\d+)(*:96)'
                    .'|delete_machine/(\\d+)(*:123)'
                .')'
            .')/?$}sD',
    ],
    [ // $dynamicRoutes
        35 => [[['_route' => '_preview_error', '_controller' => 'error_controller::preview', '_format' => 'html'], ['code', '_format'], null, null, false, true, null]],
        64 => [[['_route' => 'item_machine_get', '_controller' => 'App\\Controller\\MachineOutilController::getMachineItem'], ['id'], ['GET' => 0], null, false, true, null]],
        96 => [[['_route' => 'item_machine_update', '_controller' => 'App\\Controller\\MachineOutilController::updateMachine'], ['id'], ['PUT' => 0], null, false, true, null]],
        123 => [
            [['_route' => 'item_machine_delete', '_controller' => 'App\\Controller\\MachineOutilController::deleteMachine'], ['id'], ['DELETE' => 0], null, false, true, null],
            [null, null, null, null, false, false, 0],
        ],
    ],
    null, // $checkCondition
];
