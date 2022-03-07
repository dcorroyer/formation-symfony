<?php

namespace App\Controller\Purchase;

use App\Cart\CartService;
use App\Entity\Purchase;
use App\Entity\PurchaseItem;
use App\Form\CartConfirmationType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormFactoryInterface;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\RouterInterface;
//use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class PurchaseConfirmationController extends AbstractController
{
//    /**
//     * @var FormFactoryInterface
//     */
//    protected $formFactory;

//    /**
//     * @var RouterInterface
//     */
//    protected $router;

//    /**
//     * @var Security
//     */
//    protected $security;

    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * PurchaseConfirmationController constructor.
     *
     * @param FormFactoryInterface $formFactory
     * @param RouterInterface $router
     * @param Security $security
     * @param CartService $cartService
     * @param EntityManagerInterface $em
     */
    public function __construct(
//        FormFactoryInterface $formFactory,
//        RouterInterface $router,
//        Security $security,
        CartService $cartService,
        EntityManagerInterface $em
    )
    {
//        $this->formFactory = $formFactory;
//        $this->router      = $router;
//        $this->security    = $security;
        $this->cartService = $cartService;
        $this->em          = $em;
    }

    /**
     * @Route("/purchase/confirm", name="purchase_confirm")
     * @IsGranted("ROLE_USER", message="Vous devez être connecté pour confirmer une commande")
     */
    public function confirm(
        Request $request
//        FlashBagInterface $flashBag
    )
    {
//        Nous voulons lire les données du formulaire
        $form = $this->createForm(CartConfirmationType::class);
//        $form = $this->formFactory->create(CartConfirmationType::class);

        $form->handleRequest($request);

//        Si le formulaire n'a pas été soumis : sortir
        if (!$form->isSubmitted()) {
            $this->addFlash('warning', "Vous devez remplir le formulaire de confirmation");
//            $flashBag->add('warning', "Vous devez remplir le formulaire de confirmation");

            return $this->redirectToRoute('cart_show');
//            return new RedirectResponse($this->router->generate('cart_show'));
        }

//        Si je ne suis pas connecté : sortir
        $user = $this->getUser();
//        $user = $this->security->getUser();

//        if (!$user) {
//            throw new AccessDeniedException("Vous devez être connecté pour confirmer une commande");
//        }

//        S'il n'y a pas de produits dans mon panier : sortir
        $cartItems = $this->cartService->getDetailedCartItems();

        if (count($cartItems) === 0) {
            $this->addFlash('warning', "Vous ne pouvez pas confirmer une commande avec un panier vide");
//            $flashBag->add('warning', "Vous ne pouvez pas confirmer une commande avec un panier vide");

            return $this->redirectToRoute('cart_show');
//            return new RedirectResponse($this->router->generate('cart_show'));
        }

//        Créer une purchase
        /**
         * @var Purchase
         */
        $purchase = $form->getData();

//        Lier la purchase à l'utilisateur connecté
        $purchase->setUser($user)
            ->setPurchasedAt(new DateTime())
            ->setTotal($this->cartService->getTotal())
        ;

        $this->em->persist($purchase);

//        Lier la purchase aux produits qui sont dans le panier
//        $total = 0;

        foreach ($this->cartService->getDetailedCartItems() as $cartItem) {
            $purchaseItem = new PurchaseItem();
            $purchaseItem->setPurchase($purchase)
                ->setProduct($cartItem->product)
                ->setProductName($cartItem->product->getName())
                ->setProductPrice($cartItem->product->getPrice())
                ->setQuantity($cartItem->quantity)
                ->setTotal($cartItem->getTotal())
            ;

//            $total += $cartItem->getTotal();

            $this->em->persist($purchaseItem);
        }

//        $purchase->setTotal($total);

//        Enregistrer la commande
        $this->em->flush();

        $this->cartService->empty();

        $this->addFlash('success', "La commande a bien été enregistrée");
//        $flashBag->add('success', "La commande a bien été enregistrée");

        return $this->redirectToRoute('purchase_index');
//        return new RedirectResponse($this->router->generate('purchase_index'));
    }
}