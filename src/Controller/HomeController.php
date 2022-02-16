<?php

namespace App\Controller;

//use App\Entity\Product;
//use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function homepage(
        ProductRepository $productRepository
//        EntityManagerInterface $em
    ): Response
    {
//        $productRepository = $em->getRepository(Product::class);
//        $product           = $productRepository->find(3);
//
//        $product->setPrice(13000);
//        $em->flush();

//        $product = new Product();
//
//        $product
//            ->setName('Souris gaming')
//            ->setPrice(12000)
//            ->setSlug('souris-gaming')
//        ;
//
//        $em->persist($product);
//        $em->flush();

        $products = $productRepository->findBy([], [], 3);

        return $this->render('home.html.twig', [
            'products' => $products
        ]);
    }
}
