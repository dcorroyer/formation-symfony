<?php

namespace App\Controller\Purchase;

use App\Repository\PurchaseRepository;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentController extends AbstractController
{
    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     */
    public function showCardForm($id, PurchaseRepository $purchaseRepository): Response
    {
        $purchase = $purchaseRepository->find($id);

        if (!$purchase) {
            return $this->redirectToRoute('cart_show');
        }

        Stripe::setApiKey('sk_test_51KaxGsGzMoPIs4eyUDzTOQ66ovWzwsL2NPiVs073Sd0Wb4E3RI822QWb7DNSfOfnoae3aD50rV58w8V6F11sHSxL00d86DcJ8n');

        $intent = PaymentIntent::create([
            'amount'   => $purchase->getTotal(),
            'currency' => 'eur',
            'automatic_payment_methods' => [
                'enabled' => true,
            ],
        ]);

        return $this->render('purchase/payment.html.twig', [
            'clientSecret' => $intent->client_secret
        ]);
    }
}
