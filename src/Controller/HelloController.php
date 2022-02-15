<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Cocur\Slugify\Slugify;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HelloController
{
    // protected $logger;

    // /**
    //  * constructor of the logger
    //  *
    //  * @param LoggerInterface $logger
    //  */
    // public function __construct(LoggerInterface $logger)
    // {
    //     $this->logger = $logger;
    // }

    // protected $calculator;

    // /**
    //  * Constructor of the calculator from Calculator service
    //  *
    //  * @param Calculator $calculator
    //  */
    // public function __construct(Calculator $calculator)
    // {
    //     $this->calculator = $calculator;
    // }

    /**
     * @Route("/hello/{name}", name="hello")
     */
    public function hello(LoggerInterface $logger, Calculator $calculator, Slugify $slugify, $name = "World"): Response
    {
        $slugify = new Slugify();
        dd($slugify->slugify("Hello World"));

        // $this->logger->info("Mon message de log !");
        $logger->info("Mon message de log !");

        // $tva = $this->calculator->calcul(100);
        $tva = $calculator->calcul(80);

        dd($tva);

        return new Response("Hello $name");
    }
}
