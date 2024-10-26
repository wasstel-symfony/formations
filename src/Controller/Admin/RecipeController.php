<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;

#[Route("admin/recipe", name: "admin_recipe_")]
class RecipeController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(Request $request, RecipeRepository $repository, EntityManagerInterface $entityManager): Response
    {
//        $recipe = new Recipe();
//        $recipe->setTitle('Gombo soupa');
//        $recipe->setDuration(30);
//        $recipe->setContent('Gombo soupa');
//        $recipe->setCreatedAt(new \DateTimeImmutable('now'));
//        $recipe->setUpdatedAt(new \DateTimeImmutable('now'));
//        $recipe->setSlug('gombo-soupa');
//        $entityManager->persist($recipe);
//        $entityManager->flush();
//        dd($repository->findTotalDuration());
        $recipes = $repository->findAll();
        return $this->render('admin/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

//    #[Route('show/{slug}-{id}', name: 'app_show_recipe', requirements: ['id' => '\d+', 'slug' =>'[a-z0-9-]+'], methods: ['GET'])]
//    public function show(Request $request, RecipeRepository $repository, int $id): Response
//    {
//        $recipe = $repository->find($id);
//        if ($recipe->getSlug() !== $request->attributes->get('slug')) {
//            return $this->redirectToRoute('app_show_recipe', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
//        }
//        return $this->render('recipe/show.html.twig', [
//            'recipe' => $recipe,
//        ]);
//    }

    #[Route('/{id}/edit', name: 'edit', requirements: ['id' => Requirement::DIGITS])]
    public function edit(Request $request, EntityManagerInterface $em, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Recipe updated.');
            return $this->redirectToRoute('admin_recipe_index');
        }
        return $this->render('admin/recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            $recipe->setCreatedAt(new \DateTimeImmutable());
//            $recipe->setUpdatedAt(new \DateTimeImmutable());
            $em->persist($recipe);
            $em->flush();
            $this->addFlash('success', 'Recipe created.');
            return $this->redirectToRoute('admin_recipe_index');
        }
        return $this->render('admin/recipe/create.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('/{id}/delete', name: 'delete',  requirements: ['id' => Requirement::DIGITS], methods: ['DELETE'])]
    public function remove(EntityManagerInterface $em, Recipe $recipe): Response
    {
        $em->remove($recipe);
        $em->flush();
        $this->addFlash('success', 'Recipe deleted.');
        return $this->redirectToRoute('admin_recipe_index');
    }
    
}
