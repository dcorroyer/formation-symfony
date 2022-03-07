<?php

namespace App\Controller\Purchase;

use App\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\Routing\RouterInterface;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
//use Symfony\Component\Security\Core\Security;
//use Twig\Environment;

class PurchasesListController extends AbstractController
{
//    /**
//     * @var Security
//     */
//    protected $security;
//
//    /**
//     * @var RouterInterface
//     */
//    protected $router;
//
//    /**
//     * @var Environment
//     */
//    protected $twig;
//
//    /**
//     * PurchasesListController constructor.
//     *
//     * @param Security $security
//     * @param RouterInterface $router
//     * @param Environment $twig
//     */
//    public function __construct(Security $security, RouterInterface $router, Environment $twig)
//    {
//        $this->security = $security;
//        $this->router = $router;
//        $this->twig = $twig;
//    }

    /**
     * Function to show the purchases list
     *
     * @Route("/purchases", name="purchase_index")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour accéder à vos commandes")
     *
     * @return Response
     */
    public function index(): Response
    {
        /**
         * @var User
         */
        $user = $this->getUser();
//        $user = $this->security->getUser();

//        if (!$user) {
//            throw new AccessDeniedException("Vous devez être connecté pour accéder à vos commandes");
////            $url = $this->router->generate('homepage');
////
////            return new RedirectResponse($url);
//        }

        return $this->render('purchase/index.html.twig', [
            'purchases' => $user->getPurchases()
        ]);
//        $html = $this->twig->render('purchase/index.html.twig', [
//            'purchases' => $user->getPurchases()
//        ]);

//        return new Response($html);
    }
}