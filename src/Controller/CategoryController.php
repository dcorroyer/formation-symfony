<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class CategoryController extends AbstractController
{
    /**
     * @var CategoryRepository
     */
    protected $categoryRepository;

    /**
     * CategoryController constructor.
     *
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @return Response
     */
    public function renderMenuList(): Response
    {
        $categories = $this->categoryRepository->findAll();

        return $this->render('category/_menu.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/category/create", name="category_create")
     *
     * @param Request $request
     * @param SluggerInterface $slugger
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function create(
        Request $request,
        SluggerInterface $slugger,
        EntityManagerInterface $em
    ): Response
    {
        $category = new Category();
        $form     = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('category/create.html.twig', [
            'formView' => $formView
        ]);
    }

    // @IsGranted("ROLE_ADMIN", message="Vous n'avez pas accès!")
    // @IsGranted("CAN_EDIT", subject="id", message="Vous n'êtes pas le propriétaire de la catégorie")
    /**
     * @Route("/admin/category/{id}/edit", name="category_edit")
     *
     * @param $id
     * @param CategoryRepository $categoryRepository
     * @param SluggerInterface $slugger
     * @param Request $request
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function edit(
        $id,
        CategoryRepository $categoryRepository,
        SluggerInterface $slugger,
        Request $request,
        EntityManagerInterface $em
        // Security $security
    ): Response
    {
        // $this->denyAccessUnlessGranted('ROLE_ADMIN', null, "Vous n'avez pas accès!");
        // $user = $security->getUser();
        // $user = $this->getUser();

        // if (!$user) {
        //     return $this->redirectToRoute('security_login');
        // }

        // // if (!in_array("ROLE_ADMIN", $user->getRoles())) {
        // // if ($security->isGranted('ROLE_ADMIN') === false) {
        // if ($this->isGranted('ROLE_ADMIN') === false) {
        //     throw new AccessDeniedException("Vous n'avez pas le droit d'éditer une catégorie");
        // }

        $category = $categoryRepository->find($id);

        if (!$category) {
            throw new NotFoundHttpException("La catégorie n'existe pas!");
        }

        // $this->denyAccessUnlessGranted('CAN_EDIT', $category, "Vous n'êtes pas le propriétaire de la catégorie");

        // $user = $this->getUser();

        // if (!$user) {
        //     return $this->redirectToRoute('security_login');
        // }

        // if ($user !== $category->getOwner()) {
        //     throw new AccessDeniedException("Vous n'êtes pas le propriétaire de la catégorie");
        // }

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug(strtolower($slugger->slug($category->getName())));

            $em->flush();

            return $this->redirectToRoute('homepage');
        }

        $formView = $form->createView();

        return $this->render('category/edit.html.twig', [
            'category' => $category,
            'formView' => $formView
        ]);
    }
}
