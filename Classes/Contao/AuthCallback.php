<?php


namespace MyParcelCom\ContaoLib\Classes\Contao;


use Contao\Backend;
use Contao\Database;
use Contao\System;
use MyParcelCom\ContaoLib\Classes\Event\LoadStatusOptionsEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class AuthCallback extends Backend
{
    public function getAvailableOrderStatus()
    {
        /** @var EventDispatcherInterface $dispatcher */
        $dispatcher = System::getContainer()->get('event_dispatcher');
        $event = new LoadStatusOptionsEvent();
        $dispatcher->dispatch($event::NAME, $event);
        return $event->getOptions();
    }
}