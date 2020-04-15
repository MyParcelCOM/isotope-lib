<?php

/**
 * Backend Modules
 */
array_insert($GLOBALS['BE_MOD'], array_search('content', array_keys($GLOBALS['BE_MOD'])) + 1, array
(
    'myparcelcom' => [
        'myparcelcom_api_auth'   => ['tables' => ['tl_myparcelcom_api_auth']],
        'myparcelcom_api_shipment' => ['tables' => ['tl_myparcelcom_api_shipment']],
    ]
));