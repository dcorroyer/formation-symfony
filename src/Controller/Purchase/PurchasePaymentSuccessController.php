<?php

namespace App\Controller\Purchase;

use App\Entity\Purchase;
use App\Cart\CartService;
use App\Event\PurchaseSuccessEvent;
use App\Repository\PurchaseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PurchasePaymentSuccessController extends AbstractController
{
    /**
     * @var PurchaseRepository
     */
    protected $purchaseRepository;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    public function __construct(PurchaseRepository $purchaseRepository, EntityManagerInterface $em, CartService $cartService)
    {
        $this->purchaseRepository = $purchaseRepository;
        $this->cartService        = $cartService;
        $this->em                 = $em;
    }

    /**
     * @Route("/purchase/terminate/{id}", name="purchase_payment_success")
     * @IsGranted("ROLE_USER")
     */
    public function success($id, EventDispatcherInterface $dispatcher)
    {
        $purchase = $this->purchaseRepository->find($id);

        if (
            !$purchase ||
            ($purchase && $purchase->getUser() !== $this->getUser()) ||
            ($purchase && $purchase->getStatus() === Purchase::STATUS_PAID)
        ) {
            $this->addFlash('warning', "La commande n'existe pas.");

            return $this->redirectToRoute('purchase_index');
        }

        $purchase->setStatus(Purchase::STATUS_PAID);
        $this->em->flush();

        $this->cartService->empty();

        $purchaseEvent = new PurchaseSuccessEvent($purchase);
        $dispatcher->dispatch($purchaseEvent, 'purchase.success');

        $this->addFlash('success', "La commande a été payée avec succès!");

        return $this->redirectToRoute('purchase_index');
    }
}
