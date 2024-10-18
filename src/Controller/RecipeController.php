<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RecipeController extends AbstractController
{

    #[Route('/recipe', name: 'app_recipe')]
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
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipes,
        ]);
    }

    #[Route('show/{slug}-{id}', name: 'app_show_recipe', requirements: ['id' => '\d+', 'slug' =>'[a-z0-9-]+'], methods: ['GET'])]
    public function show(Request $request, RecipeRepository $repository, int $id): Response
    {
        $recipe = $repository->find($id);
        if ($recipe->getSlug() !== $request->attributes->get('slug')) {
            return $this->redirectToRoute('app_show_recipe', ['id' => $recipe->getId(), 'slug' => $recipe->getSlug()]);
        }
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
        ]);
    }

    #[Route('/recipe/{id}/edit', name: 'app_recipe_edit')]
    public function edit(Request $request, RecipeRepository $repository, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }
    
}
