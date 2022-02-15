<?php

namespace App\Controller;

use App\Taxes\Calculator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    protected $calculator;

    /**
     * Constructor of the calculator from Calculator service
     *
     * @param Calculator $calculator
     */
    public function __construct(Calculator $calculator)
    {
        $this->calculator = $calculator;
    }
    
    /**
     * @Route("/", name="index")
     */
    public function index() {
        $tva = $this->calculator->calcul(80);
        dump($tva);die();
    }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET", "POST"}, host="localhost", schemes={"https", "http"})
     */
    public function test(Request $request, $age): Response
    {
        //$age     = $request->query->get('age', 0);
        //$age = $request->attributes->get('age');

        return new Response("Vous avez $age ans.");
    }
}
