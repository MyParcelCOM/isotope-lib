<?php
/*
  * This file is part of con4gis,
  * the gis-kit for Contao CMS.
  *
  * @package   	con4gis
  * @version    7
  * @author  	con4gis contributors (see "authors.txt")
  * @license 	LGPL-3.0-or-later
  * @copyright 	Küstenschmiede GmbH Software & Design
  * @link       https://www.con4gis.org
  */


/**
 * Table tl_myparcelcom_api_shipment
 */


$strName = 'tl_myparcelcom_api_shipment';

$GLOBALS['TL_DCA'][$strName] = array
(
    //config
    'config' => array
    (
        'dataContainer'     => 'Table',
        'enableVersioning'  => true,
        'notCreatable'      => true,
        'sql'               => array
        (
            'keys' => array
            (
                'id' => 'primary',
            )
        )
    ),
    
    
    //List
    'list' => array
    (
        'sorting' => array
        (
            'mode'              => 2,
            'fields'            => array('orderID DESC'),
            'panelLayout'       => 'filter;sort,search,limit',
            'headerFields'      => array('orderID'),
        ),
        
        'label' => array
        (
            'fields'            => array('shipmentID', 'status', 'orderID'),
            'showColumns'       => true,
        ),
        
        'operations' => array
        (
            'edit' => array
            (
                'label'         => $GLOBALS['TL_LANG'][$strName]['edit'],
                'href'          => 'act=edit',
                'icon'          => 'edit.svg',
            ),
            'copy' => array
            (
                'label'         => $GLOBALS['TL_LANG'][$strName]['copy'],
                'href'          => 'act=copy',
                'icon'          => 'copy.svg',
            ),
            'delete' => array
            (
                'label'         => $GLOBALS['TL_LANG'][$strName]['delete'],
                'href'          => 'act=delete',
                'icon'          => 'delete.svg',
                'attributes'    => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false;Backend.getScrollOffset()"',
            ),
            'show' => array
            (
                'label'         => $GLOBALS['TL_LANG'][$strName]['show'],
                'href'          => 'act=show',
                'icon'          => 'show.svg',
            ),
        )
    ),
    
    //Palettes
    'palettes' => array
    (
        'default'   =>  '{data_legend},shipmentID,status,weight;',
    ),
    
    
    //Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(11) unsigned NOT NULL auto_increment"
        ),
        
        'tstamp' => array
        (
            'sql' => "int(11) unsigned NOT NULL default '0'"
        ),
        
        'shipmentID' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['shipmentID'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long', 'unique' => true),
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
        
        'status' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['status'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'reference'         => $GLOBALS['TL_LANG'][$strName]['statusrefs'],
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
        
        'weight' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['weight'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'sql'               => "int(11) NOT NULL default 0"
        ],
    
        'authID' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['authID'],
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'sql'               => "int(11) NOT NULL default 0"
        ],
        
        'orderID' => [
            'label'             => "OrderID",
            'sql'               => ['type' => 'integer', 'notnull' => false, 'default' => 0, 'unique' => true]
        ]
    )
);

