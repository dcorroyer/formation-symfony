<?php

namespace App\EventDispatcher;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

class PrenomSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'kernel.request'    => 'addPrenomToAttributes',
            'kernel.controller' => 'test1',
            'kernel.response'   => 'test2'
        ];
    }

    public function addPrenomToAttributes(RequestEvent $requestEvent)
    {
        $requestEvent->getRequest()->attributes->set('prenom', 'Lior');
    }

    public function test1()
    {
    }

    public function test2()
    {
    }
}
