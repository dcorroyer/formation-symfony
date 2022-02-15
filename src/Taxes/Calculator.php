<?php

namespace App\Taxes;

use Psr\Log\LoggerInterface;

class Calculator 
{
    protected $logger;
    protected $tva;

    /**
     * constructor of the logger
     *
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger, float $tva)
    {
        $this->logger = $logger;
        $this->tva    = $tva;
    }

    /**
     * Calcul TVA
     *
     * @param float $prix
     *
     * @return float
     */
    public function calcul(float $prix): float
    {
        $this->logger->info("Un calcul a lieu: $prix");
        
        return $prix * (20 / 100);
    }
}