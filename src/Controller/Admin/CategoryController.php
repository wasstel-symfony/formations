<?php

namespace App\Controller\Admin;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/admin/category', name: 'admin_category_')]
#[IsGranted("ROLE_ADMIN")]
class CategoryController extends AbstractController
{
    #[Route(name: 'index', methods: ['GET'])]
    public function index(CategoryRepository $repository): Response
    {

        return $this->render('admin/category/index.html.twig', [
            'categories' => $repository->findAll(),
        ]);
    }


    #[Route('show/{id}', name: 'show', requirements: ['id' => Requirement::DIGITS])]
    public function show(): Response
    {

    }

    #[Route('create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($category);
            $em->flush();
            $this->addFlash('success', 'Category created.');
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/create.html.twig',
            [
                'form' => $form
            ]
        );
    }

    #[Route('{id}/edit', name: 'edit', requirements: ['id' => Requirement::DIGITS], methods: ['POST', 'GET'])]
    public function edit(Category $category, Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Category updated.');
            return $this->redirectToRoute('admin_category_index');
        }
        return $this->render('admin/category/edit.html.twig',
            [
                'form' => $form,
                'category' => $category
            ]
        );
    }

    #[Route('{id}/delete', name: 'delete', requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function delete(Category $category, EntityManagerInterface $em): Response
    {
        $em->remove($category);
        $em->flush();
        $this->addFlash('success', 'Category deleted.');
        return $this->redirectToRoute('admin_category_index');
    }
}
