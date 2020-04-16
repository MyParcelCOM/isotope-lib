<?php

/**
 * Backend Modules
 */

use MyParcelcom\ContaoApi\Classes\Contao\ShipmentCallback;

array_insert($GLOBALS['BE_MOD'], array_search('content', array_keys($GLOBALS['BE_MOD'])) + 1, array
(
    'myparcelcom' => [
        'myparcelcom_api_auth'   => ['tables' => ['tl_myparcelcom_api_auth']],
        'myparcelcom_api_shipment' => ['tables' => ['tl_myparcelcom_api_shipment']],
    ]
));

$GLOBALS['BE_MOD']['myparcelcom']['myparcelcom_api_shipment']['registerShipment'] = [ShipmentCallback::class, 'registerShipment'];
$GLOBALS['BE_MOD']['myparcelcom']['myparcelcom_api_shipment']['downloadLabel'] = [ShipmentCallback::class, 'downloadLabel'];