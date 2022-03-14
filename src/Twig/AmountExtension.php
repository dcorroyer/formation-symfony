<?php

namespace App\Twig;

use Twig\TwigFilter;
use Twig\Extension\AbstractExtension;

class AmountExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('amount', [$this, 'amount'])
        ];
    }

    public function amount($value, string $symbol = '€', string $decSep = ',', string $thousandSep = ' ')
    {
        $finalValue = $value / 100;

        // $finalValue = number_format($finalValue, 2, ',', ' ');
        $finalValue = number_format($finalValue, 2, $decSep, $thousandSep);

        return $finalValue . ' ' . $symbol;
    }
}