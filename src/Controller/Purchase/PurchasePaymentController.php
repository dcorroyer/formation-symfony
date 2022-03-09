<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Repository\PurchaseRepository;
use App\Stripe\StripeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PurchasePaymentController extends AbstractController
{
    /**
     * @Route("/purchase/pay/{id}", name="purchase_payment_form")
     * @IsGranted("ROLE_USER")
     */
    public function showCardForm($id, PurchaseRepository $purchaseRepository, StripeService $stripeService): Response
    {
        $purchase = $purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash('warning', "La commande n'existe pas.");

            return $this->redirectToRoute('cart_show');
        }

        $intent = $stripeService->getPaymentIntent($purchase);
//        Stripe::setApiKey('sk_test_51KaxGsGzMoPIs4eyUDzTOQ66ovWzwsL2NPiVs073Sd0Wb4E3RI822QWb7DNSfOfnoae3aD50rV58w8V6F11sHSxL00d86DcJ8n');
//
//        $intent = PaymentIntent::create([
//            'amount'   => $purchase->getTotal(),
//            'currency' => 'eur'
//        ]);

        return $this->render('purchase/payment.html.twig', [
            'clientSecret'    => $intent->client_secret,
            'purchase'        => $purchase,
            'stripePublicKey' => $stripeService->getPublicKey()
        ]);
    }
}
