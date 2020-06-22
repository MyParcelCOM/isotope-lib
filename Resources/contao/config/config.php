<?php

use MyParcelCom\IsotopeLib\Classes\OrderCallback;

$GLOBALS['myparcelcom']['availableShops']['isotope'] = "Isotope eCommerce";

$GLOBALS['BE_MOD']['isotope']['iso_orders']['transferShipmentData'] = [OrderCallback::class, 'transferShipmentData'];
$GLOBALS['BE_MOD']['isotope']['iso_orders']['trackShipment'] = [OrderCallback::class, 'trackShipment'];
$GLOBALS['BE_MOD']['isotope']['iso_orders']['transferAllShipments'] = [OrderCallback::class, 'transferAllShipments'];
$GLOBALS['BE_MOD']['isotope']['iso_orders']['syncAllShipments'] = [OrderCallback::class, 'syncAllShipments'];
