<?php


$GLOBALS['TL_DCA']['tl_iso_product']['fields']['shipping_dimensions'] = [
    'label'             => $GLOBALS['TL_LANG']['tl_iso_product']['shipping_dimensions'],
    'inputType'         => 'text',
    'eval'              => array('mandatory' => false, 'tl_class' => 'long clr'),
    'sql'               => "varchar(255) NOT NULL default ''",
    'attributes'        => [
        'legend' => 'shipping_legend',
        'type' => \Isotope\Model\Attribute\TextField::class
    ]
];

$GLOBALS['TL_DCA']['tl_iso_product']['fields']['origin_country_code'] = [
    'label'             => $GLOBALS['TL_LANG']['tl_iso_product']['origin_country_code'],
    'inputType'         => 'text',
    'eval'              => array('mandatory' => false, 'tl_class' => 'long clr'),
    'sql'               => "varchar(255) NOT NULL default ''",
    'attributes'        => [
        'legend' => 'shipping_legend',
        'type' => \Isotope\Model\Attribute\TextField::class
    ]
];

$GLOBALS['TL_DCA']['tl_iso_product']['palettes']['shipping_legend'] =
    str_replace(",shipping_exempt", ",shipping_exempt,shipping_dimensions,origin_country_code", $GLOBALS['TL_DCA']['tl_iso_product']['palettes']['shipping_legend']);