<?php

namespace App\Stripe;

use App\Entity\Purchase;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class StripeService
{
    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @var string
     */
    protected $publicKey;

    public function __construct(string $secretKey, string $publicKey)
    {
        $this->secretKey = $secretKey;
        $this->publicKey = $publicKey;
    }

    public function getPublicKey(): string
    {
        return $this->publicKey;
    }

    /**
     * @param Purchase $purchase
     *
     * @return PaymentIntent
     */
    public function getPaymentIntent(Purchase $purchase): PaymentIntent
    {
        Stripe::setApiKey($this->secretKey);

        return PaymentIntent::create([
            'amount'   => $purchase->getTotal(),
            'currency' => 'eur'
        ]);
    }
}
