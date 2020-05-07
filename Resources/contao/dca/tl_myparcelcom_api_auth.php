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
 * Table tl_myparcelcom_api_auth
 */

use MyParcelCom\ContaoApi\Classes\Contao\AuthCallback;

$strName = 'tl_myparcelcom_api_auth';

$GLOBALS['TL_DCA'][$strName] = array
(
    //config
    'config' => array
    (
        'dataContainer'     => 'Table',
        'enableVersioning'  => true,
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
            'fields'            => array('name ASC'),
            'panelLayout'       => 'filter;sort,search,limit',
            'headerFields'      => array('name', 'type'),
        ),
        
        'label' => array
        (
            'fields'            => array('name'),
            'showColumns'       => true,
        ),
        
        'global_operations' => array
        (
            'all' => [
                'label'         => $GLOBALS['TL_LANG']['MSC']['all'],
                'href'          => 'act=select',
                'class'         => 'header_edit_all',
                'attributes'    => 'onclick="Backend.getScrollOffSet()" accesskey="e"'
            ]
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
            )
        )
    ),
    
    //Palettes
    'palettes' => array
    (
        'default'   =>  '{data_legend},name,clientid,clientsecret,apiUrl,authUrl,shopName,connectWith,myparcelStatus;',
    ),
    
    
    //Fields
    'fields' => array
    (
        'id' => array
        (
            'sql' => "int(10) unsigned NOT NULL auto_increment"
        ),
    
        'tstamp' => array
        (
            'sql' => "int(10) unsigned NOT NULL default '0'"
        ),
        
        'name' => array
        (
            'label'             => $GLOBALS['TL_LANG'][$strName]['name'],
            'flag'              => 1,
            'sorting'           => true,
            'default'           => '',
            'search'            => true,
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'exclude'           => true,
            'sql'               => "varchar(255) NOT NULL default ''"
        ),
        
        'clientid' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['clientid'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'exclude'           => true,
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
    
        'clientsecret' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['clientsecret'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'exclude'           => true,
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
    
        'apiUrl' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['apiUrl'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'exclude'           => true,
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
    
        'authUrl' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['authUrl'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'exclude'           => true,
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
    
        'shopName' => [
            'label'             => $GLOBALS['TL_LANG'][$strName]['shopName'],
            'inputType'         => 'text',
            'eval'              => array('mandatory' => true, 'tl_class' => 'long'),
            'exclude'           => true,
            'sql'               => "varchar(255) NOT NULL default ''"
        ],
    
        'connectWith' => array
        (
            'label'             => $GLOBALS['TL_LANG'][$strName]['connectWith'],
            'inputType'         => 'select',
            'options'           => $GLOBALS['myparcelcom']['availableShops'],
            'eval'              => array('mandatory' => false, 'tl_class' => 'long', 'includeBlankOption' => true),
            'exclude'           => true,
            'sql'               => "varchar(50) NOT NULL default ''"
        ),
    
        'myparcelStatus' => array
        (
            'label'             => $GLOBALS['TL_LANG'][$strName]['myparcelStatus'],
            'inputType'         => 'select',
            'options_callback'  => [AuthCallback::class, 'getAvailableOrderStatus'],
            'eval'              => array('mandatory' => false, 'tl_class' => 'long', 'includeBlankOption' => true),
            'exclude'           => true,
            'sql'               => "int(10) NOT NULL default 0"
        ),
    
        'trackingStatus' => array
        (
            'label'             => $GLOBALS['TL_LANG'][$strName]['trackingStatus'],
            'inputType'         => 'select',
            'options_callback'  => [AuthCallback::class, 'getAvailableOrderStatus'],
            'eval'              => array('mandatory' => false, 'tl_class' => 'long', 'includeBlankOption' => true),
            'exclude'           => true,
            'sql'               => "int(10) NOT NULL default 0"
        ),
    )
);

