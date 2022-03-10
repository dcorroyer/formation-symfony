<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProductViewEmailSubscriber implements EventSubscriberInterface
{
    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            'product.view' => 'sendEmail'
        ];
    }

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * Constructor function
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function sendEmail(ProductViewEvent $productViewEvent)
    {
        $this->logger->info("Email envoyé pour le produit n. " . $productViewEvent->getProduct()->getId());
    }
}
