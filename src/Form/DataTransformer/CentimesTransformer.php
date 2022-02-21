<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class CentimesTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $value
     *
     * @return float|void
     */
    public function transform($value)
    {
        if ($value === null) {
            return;
        }

        return $value / 100;
    }

    /**
     * @param mixed $value
     * @return float|void
     */
    public function reverseTransform($value)
    {
        if ($value === null) {
            return;
        }

        return $value * 100;
    }
}