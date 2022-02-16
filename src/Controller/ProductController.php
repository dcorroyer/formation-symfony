<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category")
     *
     * @param CategoryRepository $categoryRepository
     * @param $slug
     *
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository, $slug): Response
    {
        $category = $categoryRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$category) {
            throw $this->createNotFoundException("La catégorie demandée n'existe pas!");
//            throw new NotFoundHttpException("La catégorie demandée n'existe pas!");
        }

        return $this->render('product/category.html.twig', [
            'slug'     => $slug,
            'category' => $category
        ]);
    }

    /**
     * @Route("/{category_slug}/{slug}", name="product_show")
     *
     * @param ProductRepository $productRepository
     * @param $slug
     *
     * @return Response
     */
    public function show(
        ProductRepository $productRepository,
//        UrlGeneratorInterface $urlGenerator,
        $slug): Response
    {
        $product = $productRepository->findOneBy([
            'slug' => $slug
        ]);

        if (!$product) {
            throw $this->createNotFoundException("Le produit demandé n'existe pas!");
        }

        return $this->render('product/show.html.twig', [
            'slug'         => $slug,
            'product'      => $product,
//            'urlGenerator' => $urlGenerator
        ]);
    }
}
