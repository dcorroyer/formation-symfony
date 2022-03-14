<?php

namespace App\EventDispatcher;

use Psr\Log\LoggerInterface;
use App\Event\ProductViewEvent;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mime\Address;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
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
     * @var MailerInterface
     */
    protected $mailer;

    /**
     * Constructor function
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, MailerInterface $mailer)
    {
        $this->logger = $logger;
        $this->mailer = $mailer;
    }

    public function sendEmail(ProductViewEvent $productViewEvent)
    {
        $productId = $productViewEvent->getProduct()->getId();

        // $email = new Email();
        // $email = new TemplatedEmail();
        // $email->from(new Address("contact@mail.com", "Infos de la boutique"))
        //     ->to("admin@mail.com")
        //     ->subject("Visite du produit n. " . $productId)
        //     ->htmlTemplate('email/product_view.html.twig')
        //     ->context([
        //         'product' => $productViewEvent->getProduct()
        //     ])
        //     // ->html("<h1>Visite du produit {$productId}</h1>")
        //     // ->text("Un visiteur est en train de voir la page du produit n. " . $productId)
        // ;

        // $this->mailer->send($email);

        $this->logger->info("Email envoyé pour le produit n. " . $productId);
    }
}
