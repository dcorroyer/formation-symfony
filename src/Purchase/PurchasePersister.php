<?php

namespace App\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class PurchasePersister
{
    /**
     * @var Security
     */
    protected $security;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * PurchasePersister constructor.
     *
     * @param Security $security
     * @param CartService $cartService
     * @param EntityManagerInterface $em
     */
    public function __construct(Security $security, CartService $cartService, EntityManagerInterface $em)
    {
        $this->security    = $security;
        $this->cartService = $cartService;
        $this->em          = $em;
    }

    /**
     * @param Purchase $purchase
     */
    public function storePurchase(Purchase $purchase)
    {
        $purchase->setUser($this->security->getUser())
            ->setPurchasedAt(new DateTime())
            ->setTotal($this->cartService->getTotal())
        ;

        $this->em->persist($purchase);

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal())
            ;

            $this->em->persist($purchaseItem);
        }

        $this->em->flush();
    }
}