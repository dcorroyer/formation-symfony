<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormFactoryInterface;
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

    /**
     * @Route("/admin/product/create", name="product_create")
     *
     * @param FormFactoryInterface $factory
     * @param CategoryRepository $categoryRepository
     *
     * @return Response
     */
    public function create(FormFactoryInterface $factory, CategoryRepository $categoryRepository): Response
    {
        $builder = $factory->createBuilder();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du produit',
                'attr'  => [
                    'class'       => 'form-control',
                    'placeholder' => 'Tapez le nom du produit'
                ],
            ])
            ->add('shortDescription', TextareaType::class, [
                'label' => 'Description courte',
                'attr'  => [
                    'class'       => 'form-control',
                    'placeholder' => 'Tapez une description courte'
                ],
            ])
            ->add('price', MoneyType::class, [
                'label' => 'Prix du produit',
                'attr'  => [
                    'class'       => 'form-control',
                    'placeholder' => 'Tapez le prix du produit en euros.'
                ]
            ]);

        $options = [];

        foreach ($categoryRepository->findAll() as $category) {
            $options[$category->getName()] = $category->getId();
        }

        $builder
            ->add('category', ChoiceType::class, [
                'label' => 'Catégorie du produit',
                'attr'  => [
                    'class' => 'form-control'
                ],
                'placeholder' => '-- Choisir une catégorie --',
                'choices' => $options
            ])
        ;

        $form = $builder->getForm();

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }
}
