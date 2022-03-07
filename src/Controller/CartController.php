<?php

namespace App\Controller;

use App\Cart\CartService;
use App\Form\CartConfirmationType;
use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
// use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
//use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
//use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class CartController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;
    /**
     * @var CartService
     */
    protected $cartService;

    /**
     * CartController constructor.
     *
     * @param ProductRepository $productRepository
     * @param CartService $cartService
     */
    public function __construct(ProductRepository $productRepository, CartService $cartService)
    {
        $this->productRepository = $productRepository;
        $this->cartService       = $cartService;
    }

    /**
     * @Route("/cart/add/{id}", name="cart_add", requirements={"id"="\d+"})
     */
    public function add(
        $id,
        // Request $request,
//        ProductRepository $productRepository,
//        SessionInterface $session
        // FlashBagInterface $flashbag
//        CartService $cartService,
        Request $request
    ): Response
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas!");
        }

        // $cart = $request->getSession()->get('cart', []);
//        $cart = $session->get('cart', []);
//
//        if (array_key_exists($id, $cart)) {
//            $cart[$id]++;
//        } else {
//            $cart[$id] = 1;
//        }
//
//        // $request->getSession()->set('cart', $cart);
//        $session->set('cart', $cart);

        // $request->getSession()->remove('cart');
        // $session->remove('cart');

        // /** @var FlashBag */
        // $flashbag = $session->getBag('flashes');

        $this->cartService->add($id);

        $this->addFlash('success', "Le produit $id a bien été ajouté au panier !");
        // $flashbag->add('success', "Le produit $id a bien été ajouté au panier!");
        // $flashbag->add('warning', "Attention!");

        if ($request->query->get('returnToCart')) {
            return $this->redirectToRoute('cart_show');
        }

        return $this->redirectToRoute('product_show', [
            'category_slug' => $product->getCategory()->getSlug(),
            'slug'          => $product->getSlug()
        ]);
    }

    /**
     * @Route("/cart", name="cart_show")
     */
    public function show(
//        SessionInterface $session,
//        ProductRepository $productRepository,
//        CartService $cartService
    ): Response
    {
        $form = $this->createForm(CartConfirmationType::class);
        $detailedCart = $this->cartService->getDetailedCartItems();
        $total        = $this->cartService->getTotal();
//        $detailedCart = [];
//        $total        = 0;
//
//        foreach ($session->get('cart') as $id => $quantity) {
//            $product = $productRepository->find($id);
//
//            $detailedCart[] = [
//                'product'  => $product,
//                'quantity' => $quantity,
//            ];
//
//            $total += ($product->getPrice() * $quantity);
//        }
        
        return $this->render('cart/index.html.twig', [
            'items'            => $detailedCart,
            'total'            => $total,
            'confirmationForm' => $form->createView()
        ]);
    }

    /**
     * @Route("/cart/delete/{id}", name="cart_delete", requirements={"id": "\d+"})
     */
    public function delete(
        $id
//        ProductRepository $productRepository,
//        CartService $cartService
    )
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas être supprimé !");
        }

        $this->cartService->remove($id);

        $this->addFlash('success', "Le produit a bien été supprimé du panier");

        return $this->redirectToRoute('cart_show');
    }

    /**
     * @Route("/cart/decrement/{id}", name="cart_decrement", requirements={"id": "\d+"})
     */
    public function decrement(
        $id
//        CartService $cartService,
//        ProductRepository $productRepository
    )
    {
        $product = $this->productRepository->find($id);

        if (!$product) {
            throw $this->createNotFoundException("Le produit $id n'existe pas et ne peut pas être décrémenté !");
        }

        $this->cartService->decrement($id);

        $this->addFlash('success', "Le produit a bien été décrémenté");

        return $this->redirectToRoute('cart_show');
    }
}
