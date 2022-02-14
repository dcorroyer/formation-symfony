<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TestController
{
    public function index() {
        dump("oui");die();
    }

    /**
     * @Route("/test/{age<\d+>?0}", name="test", methods={"GET", "POST"}, host="localhost", schemes={"https", "http"})
     */
    public function test(Request $request, $age) {
        //$age     = $request->query->get('age', 0);
        //$age = $request->attributes->get('age');

        return new Response("Vous avez $age ans.");
    }
}
