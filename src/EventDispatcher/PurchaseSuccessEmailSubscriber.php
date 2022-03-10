<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class PurchaseSuccessEmailSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'purchase.success' => 'sendSuccessEmail'
        ];
    }

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor function
     *
     * @param LoggerInterface $loggerInterface
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        $this->logger->info("Email envoyé pour la commande n. " . $purchaseSuccessEvent->getPurchase()->getId());
    }
}