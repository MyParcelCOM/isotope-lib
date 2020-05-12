<?php

use Contao\CoreBundle\DataContainer\PaletteManipulator;

PaletteManipulator::create()
    ->addLegend('myparcel_com_legend', 'uploads_legend', PaletteManipulator::POSITION_AFTER, true)
    ->addField('myparcel_enableTransferButton', 'myparcel_com_legend',PaletteManipulator::POSITION_APPEND, 'myparcel_com_legend')
    ->applyToPalette('default', 'tl_settings');

$GLOBALS['TL_DCA']['tl_settings']['fields']['myparcel_enableTransferButton'] = [
    'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['myparcel_enableTransferButton'],
    'default'                 => false,
    'exclude'                 => true,
    'inputType'               => 'checkbox',
    'eval'                    => array('tl_class'=>'long clr'),
];