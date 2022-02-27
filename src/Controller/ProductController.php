<?php

namespace App\Controller;

//use App\Entity\Category;
use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\CategoryRepository;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
//use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
//use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
//use Symfony\Component\Form\Extension\Core\Type\FormType;
//use Symfony\Component\Form\Extension\Core\Type\MoneyType;
//use Symfony\Component\Form\Extension\Core\Type\TextareaType;
//use Symfony\Component\Form\Extension\Core\Type\TextType;
//use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormFactoryInterface;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
//use Symfony\Component\Validator\Constraints\Collection;
//use Symfony\Component\Validator\Constraints\GreaterThan;
//use Symfony\Component\Validator\Constraints\Length;
//use Symfony\Component\Validator\Constraints\LessThanOrEqual;
//use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Validator\ValidatorInterface;

//use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ProductController extends AbstractController
{
    /**
     * @Route("/{slug}", name="product_category", priority=-1)
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
//   * @param CategoryRepository $categoryRepository
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(
        FormFactoryInterface $factory,
//        CategoryRepository $categoryRepository
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response
    {
//        $builder = $factory->createBuilder(FormType::class, null, [
//            'data_class' => Product::class
//        ]);
//
//        $builder
//            ->add('name', TextType::class, [
//                'label' => 'Nom du produit',
//                'attr'  => [
////                    'class'       => 'form-control',
//                    'placeholder' => 'Tapez le nom du produit'
//                ],
//            ])
//            ->add('shortDescription', TextareaType::class, [
//                'label' => 'Description courte',
//                'attr'  => [
////                    'class'       => 'form-control',
//                    'placeholder' => 'Tapez une description courte'
//                ],
//            ])
//            ->add('price', MoneyType::class, [
//                'label' => 'Prix du produit ',
//                'attr'  => [
////                    'class'       => 'form-control',
//                    'placeholder' => 'Tapez le prix du produit en euros.'
//                ]
//            ])
//            ->add('mainPicture', UrlType::class, [
//                'label' => 'Image du produit ',
//                'attr'  => [
////                    'class'       => 'form-control',
//                    'placeholder' => 'Tapez une URL d\'image'
//                ]
//            ])
////        ;
//
////        $options = [];
////
////        foreach ($categoryRepository->findAll() as $category) {
////            $options[$category->getName()] = $category->getId();
////        }
//
////        $builder
////            ->add('category', ChoiceType::class, [
////                'label' => 'Catégorie du produit',
////                'attr'  => [
////                    'class' => 'form-control'
////                ],
////                'placeholder' => '-- Choisir une catégorie --',
////                'choices' => $options
////            ])
//
//            ->add('category', EntityType::class, [
//                'label'        => 'Catégorie du produit',
//                'attr'         => [
////                    'class' => 'form-control'
//                ],
//                'placeholder'  => '-- Choisir une catégorie --',
//                'class'        => Category::class,
////              'choice_label' => 'name'
//                'choice_label' => function (Category $category) {
//                    return strtoupper($category->getName());
//                }
//            ])
//        ;

        $product = new Product();

        $form = $this->createForm(ProductType::class, $product);
//        $builder = $factory->createBuilder(ProductType::class);
//        $form    = $builder->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
//            $data = $form->getData();
//            $product = $form->getData();
            $product->setSlug(strtolower($slugger->slug($product->getName())));

//            $product = new Product();
//            $product->setName($data['name'])
//                ->setShortDescription($data['shortDescription'])
//                ->setPrice($data['price'])
//                ->setCategory($data['category'])
//            ;
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug'          => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/create.html.twig', [
            'formView' => $formView
        ]);
    }

    /**
     * @Route("/admin/product/{id}/edit", name="product_edit")
     *
     * @param $id
     * @param ProductRepository $productRepository
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(
        $id,
        ProductRepository $productRepository,
        Request $request,
        EntityManagerInterface $em
//        UrlGeneratorInterface $urlGenerator,
//        ValidatorInterface $validator
    ): Response
    {
//        $product = new Product();
//
//        $resultat = $validator->validate($product, null, ["Default", "with-price"]);
//
//        dd($resultat);
//        $product->setName("Bonjour")
//            ->setPrice(80);
//
//        $resultat = $validator->validate($product);
//
//        if ($resultat->count() > 0) {
//            dd("Il y a des erreurs", $resultat);
//        }
//
//        dd("Tout va bien");

//        $client = [
//            'name' => 'Chamla',
//            'firstname' => 'Lior',
//            'car' => [
//                'brand' => 'Hyundai',
//                'color' => 'Black'
//            ],
//        ];
//
//        $collection = new Collection([
//            'name' => new NotBlank([
//                'message' => "Le nom ne doit pas être null."
//            ]),
//            'firstname' => [
//                new NotBlank([
//                    'message' => "Le nom ne doit pas être null."
//                ]),
//                new Length([
//                    'min' => 3,
//                    'minMessage' => "Le prénom doit contenir 3 caractères au minimum."
//                ]),
//            ],
//            'car' => new Collection([
//                'brand' => new NotBlank([
//                    'message' => "La marque de la voiture est obligatoire."
//                ]),
//                'color' => new NotBlank([
//                    'message' => "La couleur de la voiture est obligatoire. "
//                ])
//            ])
//        ]);
//
//        $resultat1 = $validator->validate($client, $collection);
//
//        if ($resultat1->count() > 0) {
//            dd("Il y a des erreurs", $resultat1);
//        }
//
//        $age = 26;
//
//        $resultat2 = $validator->validate($age, [
//            new LessThanOrEqual([
//                'value' => 90,
//                'message' => "L'age doit être inférieur à {{ compared_value }} mais vous avez donné {{ value }}"
//            ]),
//            new GreaterThan([
//                'value' => 0,
//                'message' => "L'age ne peut pas être négatif, vous avez donné {{ value }}"
//            ])
//        ]);
//
//        if ($resultat2->count() > 0) {
//            dd("Il y a des erreurs", $resultat2);
//        }
//
//        dd("Tout va bien");

        $product = $productRepository->find($id);
//        $form    = $this->createForm(ProductType::class, $product, [
//            'validation_groups' => [
//                "Default",
//                "large-name",
//                "with-price"
//            ],
//        ]);

        $form = $this->createForm(ProductType::class, $product);

//        $form->setData($product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$product = $form->getData();

            $em->flush();

//            $response = new Response();
//            $url      = $urlGenerator->generate('product_show', [
//                'category_slug' => $product->getCategory()->getSlug(),
//                'slug'          => $product->getSlug()
//            ]);
////
////            $response->headers->set('Location', $url);
////            $response->setStatusCode(302);
//
////            $response = new RedirectResponse($url);
////            return $response;
//
//            return $this->redirect($url);
            return $this->redirectToRoute('product_show', [
                'category_slug' => $product->getCategory()->getSlug(),
                'slug'          => $product->getSlug()
            ]);
        }

        $formView = $form->createView();

        return $this->render('product/edit.html.twig', [
            'product'  => $product,
            'formView' => $formView
        ]);
    }
}
