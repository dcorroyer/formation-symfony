<?php

namespace App\Controller;

//use App\Taxes\Calculator;
//use App\Taxes\Detector;
//use Cocur\Slugify\Slugify;
//use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Twig\Environment;

class HelloController extends AbstractController
{
//    protected $twig;
//
//    public function __construct(Environment $twig)
//    {
//        $this->twig = $twig;
//    }
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
    public function hello(
//        LoggerInterface $logger,
//        Calculator $calculator,
//        Slugify $slugify,
//        Detector $detector,
//        Environment $twig,
        $name = "World"
    ): Response
    {
//        dump($detector->detect(10), $detector->detect(101));die();
//
//        $slugify = new Slugify();
//        dd($slugify->slugify("Hello World"));
//
//        // $this->logger->info("Mon message de log !");
//        $logger->info("Mon message de log !");
//
//        // $tva = $this->calculator->calcul(100);
//        $tva = $calculator->calcul(80);
//
//        dd($tva);
//
//        return new Response("Hello $name");

//        $html = $twig->render('hello.html.twig', [
//        $html = $this->twig->render('hello.html.twig', [
//            'name' => $name,
//            'age'   => 33,
//            'names' => [
//                'Oui-oui',
//                'Franklin',
//                'Winnie'
//            ]
//            'ages' => [
//                12,
//                18,
//                29,
//                15
//            ],
//            'teacher' => [
//                'firstname' => 'Lior',
//                'lastname'  => 'Chamla',
//                'age'       => 33
//            ],
//            'teacher1' => ['firstname' => 'Lior', 'lastname' => 'Chamla'],
//            'teacher2' => ['firstname' => 'Winnie', 'lastname' => 'L\'ourson'],
//        ]);
//
//        return new Response($html);

        return $this->render('hello.html.twig', [
            'name' => $name
        ]);
    }

    /**
     * @Route("/example", name="example")
     */
    public function example(): Response
    {
        return $this->render('example.html.twig', [
            'age' => 33
        ]);
    }

//    /**
//     * @return Response
//     */
//    protected function render(string $path, array $variables = [])
//    {
//        $html = $this->twig->render($path, $variables);
//
//        return new Response($html);
//    }
}
