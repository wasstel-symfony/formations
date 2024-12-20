<?php

namespace App\Controller\Admin;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Security\Voter\RecipeVoter;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route("admin/recipe", name: "admin_recipe_")]
//#[IsGranted("ROLE_ADMIN")]
class RecipeController extends AbstractController
{

    #[Route('/', name: 'index')]
    #[IsGranted(RecipeVoter::LIST)]
    public function index(RecipeRepository $repository, Security $security): Response
    {
        $recipes = $repository->findWithDurationLowerThan(60);
        $userId = $security->getUser()->getId();
        $canListAll = $security->isGranted(RecipeVoter::LIST_ALL);
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
    #[IsGranted(RecipeVoter::EDIT)]
    public function edit(Request $request, EntityManagerInterface $em, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
//            /**
//             * @var UploadedFile $file
//             */
//            $file = $form->get('thumbnailFile')->getData();
//            $filename = $recipe->getId().'.'.$file->getClientOriginalExtension();
//            $file->move($this->getParameter('kernel.project_dir').'/public/recipes/images', $filename);
//            $recipe->setThumbnail($filename);
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
    #[IsGranted(RecipeVoter::CREATE)]
    public function create(Request $request, EntityManagerInterface $em): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
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
