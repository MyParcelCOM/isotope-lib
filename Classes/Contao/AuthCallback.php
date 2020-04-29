<?php


namespace MyParcelCom\ContaoApi\Classes\Contao;


use Contao\Backend;
use Contao\Database;

class AuthCallback extends Backend
{
    public function getAvailableOrderStatus()
    {
        $db = Database::getInstance();
        $arrStatus = $db->prepare("SELECT * FROM tl_iso_orderstatus")->execute()->fetchAllAssoc();
        $result = [];
        foreach ($arrStatus as $status) {
            $result[$status['id']] = $status['name'];
        }
        return $result;
    }
}