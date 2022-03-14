<?php

namespace App\EventDispatcher;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use App\Event\PurchaseSuccessEvent;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Security\Core\Security;
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
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * @var Security
     */
    protected $security;

    /**
     * Constructor function
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, MailerInterface $mailer, Security $security)
    {
        $this->logger   = $logger;
        $this->mailer   = $mailer;
        $this->security = $security;
    }

    public function sendSuccessEmail(PurchaseSuccessEvent $purchaseSuccessEvent)
    {
        /** @var User */
        $currentUser = $this->security->getUser();

        $purchase = $purchaseSuccessEvent->getPurchase();

        $email = new TemplatedEmail();
        $email->from("contact@mail.com")
            ->to(new Address($currentUser->getEmail(), $currentUser->getFullName()))
            ->subject("Votre commande ({$purchase->getId()}) a bien été prise en compte")
            ->htmlTemplate('email/purchase_success.html.twig')
            ->context([
                'purchase' => $purchase,
                'user'     => $currentUser
            ])
        ;

        $this->mailer->send($email);

        $this->logger->info("Email envoyé pour la commande n. " . $purchaseSuccessEvent->getPurchase()->getId());
    }
}
