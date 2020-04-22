<?php

/**
 * Backend Modules
 */

use MyParcelCom\ContaoApi\Classes\Contao\ShipmentCallback;
use MyParcelCom\ContaoApi\Resources\contao\models\MyParcelComAuthModel;
use MyParcelCom\ContaoApi\Resources\contao\models\MyParcelComShipmentModel;

array_insert($GLOBALS['BE_MOD'], array_search('content', array_keys($GLOBALS['BE_MOD'])) + 1, array
(
    'myparcelcom' => [
        'myparcelcom_api_auth'   => ['tables' => ['tl_myparcelcom_api_auth']],
        'myparcelcom_api_shipment' => ['tables' => ['tl_myparcelcom_api_shipment']],
    ]
));

/**
 * The available shop systems. Options should be added by the shop-system-specific bundles.
 */
$GLOBALS['myparcelcom']['availableShops'] = [];

$GLOBALS['BE_MOD']['myparcelcom']['myparcelcom_api_shipment']['registerShipment'] = [ShipmentCallback::class, 'registerShipment'];
$GLOBALS['BE_MOD']['myparcelcom']['myparcelcom_api_shipment']['downloadLabel'] = [ShipmentCallback::class, 'downloadLabel'];

$GLOBALS['TL_MODELS']['tl_myparcelcom_api_auth'] = MyParcelComAuthModel::class;
$GLOBALS['TL_MODELS']['tl_myparcelcom_api_shipment'] = MyParcelComShipmentModel::class;
