<?php

use MyParcelCom\IsotopeLib\Classes\OrderCallback;

$strName = 'tl_iso_product_collection';

$GLOBALS['TL_DCA'][$strName]['list']['operations']['transferShipmentData'] = [
    'label'         => $GLOBALS['TL_LANG'][$strName]['transferShipmentData'],
    'href'          => 'key=transferShipmentData',
    'icon'          => 'bundles/myparcelcomisotopelib/img/myparcel_blue.svg',
    'button_callback' => [OrderCallback::class, 'getTransferButton']
];
$GLOBALS['TL_DCA'][$strName]['list']['operations']['trackShipment'] = [
    'label'         => $GLOBALS['TL_LANG'][$strName]['trackShipment'],
    'href'          => 'key=trackShipment',
    'icon'          => 'bundles/myparcelcomisotopelib/img/tracking.svg',
    'button_callback' => [OrderCallback::class, 'getTrackingButton']
];

$GLOBALS['TL_DCA'][$strName]['list']['global_operations']['transferAllShipments'] = [
    'label'               => $GLOBALS['TL_LANG'][$strName]['transferAllShipments'],
    'href'                => 'key=transferAllShipments',
    'icon'                => 'bundles/myparcelcomisotopelib/img/myparcel_blue.svg',
    'button_callback'     => [OrderCallback::class, 'getTransferAllButton']
];

$GLOBALS['TL_DCA'][$strName]['list']['global_operations']['syncAllShipments'] = [
    'label'               => &$GLOBALS['TL_LANG'][$strName]['syncAllShipments'],
    'href'                => 'key=syncAllShipments',
    'icon'                => 'sync.svg'
];
