<?php

namespace App\Taxes;

class Detector
{
    protected $threshold;

    /**
     * constructor of the logger
     *
     * @param float $threshold
     */
    public function __construct(float $threshold)
    {
        $this->threshold = $threshold;
    }

    /**
     * Function to detect if a taxe is needed (boolean)
     *
     * @param float $amount
     *
     * @return bool
     */
    public function detect(float $amount): bool
    {
        if ($amount > $this->threshold) {
            return true;
        }

        return false;
    }
}