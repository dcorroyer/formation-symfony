<?php

namespace App\Cart;

use App\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var SessionInterface
     */
    protected $session;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * CartService constructor.
     *
     * @param SessionInterface $session
     * @param ProductRepository $productRepository
     */
    public function __construct(SessionInterface $session, ProductRepository $productRepository)
    {
        $this->session           = $session;
        $this->productRepository = $productRepository;
    }

    /**
     * @return array
     */
    protected function getCart(): array
    {
        return $this->session->get('cart', []);
    }

    /**
     * @param array $cart
     * @return mixed
     */
    protected function setCart(array $cart)
    {
        return $this->session->set('cart', $cart);
    }

    /**
     * blablabla
     */
    public function empty()
    {
        $this->setCart([]);
    }

    /**
     * @param int $id
     */
    public function add(int $id)
    {
//        $cart = $this->session->get('cart', []);
        $cart = $this->getCart();

//        if (array_key_exists($id, $cart)) {
//            $cart[$id]++;
//        } else {
//            $cart[$id] = 1;
//        }

        if (!array_key_exists($id, $cart)) {
            $cart[$id] = 0;
        }

        $cart[$id]++;

//        $this->session->set('cart', $cart);
        $this->setCart($cart);
    }

    /**
     * @param int $id
     */
    public function remove(int $id)
    {
//        $cart = $this->session->get('cart', []);
        $cart = $this->getCart();

        unset($cart[$id]);

//        $this->session->set('cart', $cart);
        $this->setCart($cart);
    }

    /**
     * @return int
     */
    public function getTotal(): int
    {
        $total = 0;

//        foreach ($this->session->get('cart') as $id => $quantity) {
        foreach ($this->getCart() as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

            $total += ($product->getPrice() * $quantity);
        }

        return $total;
    }

    /**
     * @param int $id
     */
    public function decrement(int $id)
    {
//        $cart = $this->session->get('cart', []);
        $cart = $this->getCart();

        if (!array_key_exists($id, $cart)) {
            return;
        }

        if ($cart[$id] === 1) {
            $this->remove($id);
            return;
        }

        $cart[$id]--;

//        $this->session->set('cart', $cart);
        $this->setCart($cart);
    }

    /**
     * @return CartItem[]
     */
    public function getDetailedCartItems(): array
    {
        $detailedCart = [];

//        foreach ($this->session->get('cart') as $id => $quantity) {
        foreach ($this->getCart() as $id => $quantity) {
            $product = $this->productRepository->find($id);

            if (!$product) {
                continue;
            }

//            $detailedCart[] = [
//                'product'  => $product,
//                'quantity' => $quantity,
//            ];

            $detailedCart[] = new CartItem($product, $quantity);
        }

        return $detailedCart;
    }
}