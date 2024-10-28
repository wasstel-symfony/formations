<?php

namespace App\Controller\API;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Requirement\Requirement;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class RecipeController extends AbstractController
{
    #[Route('/api/recipe', methods: ['GET'])]
    public function index(RecipeRepository $repository): JsonResponse
    {
        $recipes = $repository->findAll();
        return $this->json($recipes, 200, [], ['groups' => 'api_recipe']);
    }

    #[Route('/api/recipe/{id}', methods: ['GET'], requirements: ['id' => Requirement::DIGITS])]
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json($recipe, 200, [], ['groups' => ['api_recipe_show', 'api_recipe']]);
    }

    #[Route('/api/recipe', methods: ['POST'])]
    public function create(Request $request,
                           #[MapRequestPayload(serializationContext: [
                               'groups' => ['api_recipe_create']
                           ])]
                           Recipe                 $recipe,
                           EntityManagerInterface $em,
    ): JsonResponse
    {
        $recipe->setCreatedAt(new \DateTimeImmutable())->setUpdatedAt(new \DateTimeImmutable());
        $em->persist($recipe);
        $em->flush();
        return $this->json($recipe, 201, ['groups' => ['api_recipe_show',]]);


    }
}