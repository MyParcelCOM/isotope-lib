<?php


namespace MyParcelCom\IsotopeLib\Classes\Listener;


use Contao\Database;
use MyParcelCom\ContaoLib\Classes\Event\LoadStatusOptionsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class LoadStatusOptionsListener
{
    /**
     * @var Database
     */
    private $database = null;
    
    /**
     * LoadStatusOptionsListener constructor.
     * @param Database|null $database
     */
    public function __construct(Database $database = null)
    {
        $this->database = $database ?: Database::getInstance();
    }
    
    public function onLoadStatusOptionsGetIsotopeStatus(
        LoadStatusOptionsEvent $event,
        $eventName,
        EventDispatcherInterface $eventDispatcher
    ) {
        $db = Database::getInstance();
        $arrStatus = $db->prepare("SELECT * FROM tl_iso_orderstatus")->execute()->fetchAllAssoc();
        foreach ($arrStatus as $status) {
            $event->addOption($status['id'], $status['name']);
        }
    }
    
}