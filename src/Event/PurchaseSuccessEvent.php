<?php

namespace App\Event;

use App\Entity\Purchase;
use Symfony\Contracts\EventDispatcher\Event;

class PurchaseSuccessEvent extends Event
{
    /**
     * @var Purchase
     */
    private $purchase;

    /**
     * Constructor of the paremeters
     *
     * @param Purchase $purchase
     */
    public function __construct(Purchase $purchase)
    {
        $this->purchase = $purchase;
    }

    public function getPurchase(): Purchase
    {
        return $this->purchase;
    }

}