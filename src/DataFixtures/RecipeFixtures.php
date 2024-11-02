<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use FakerRestaurant\Provider\fr_FR\Restaurant;
use Symfony\Component\String\Slugger\SluggerInterface;

class RecipeFixtures extends Fixture implements DependentFixtureInterface
{
    public function __construct(private readonly SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider(new Restaurant($faker));
        $categories = ["Plat chaud", "Gouter", "Entree", "Dessert"];
        foreach ($categories as $c) {
            $category = (new Category())->setName($c)
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setSlug(strtolower($this->slugger->slug($c)));
            $manager->persist($category);
            $this->addReference($c, $category);
        }
        for ($i = 0; $i < 10; $i++) {
            $title = $faker->foodName();
            $recipe = (new Recipe())->setTitle($title)
                ->setSlug(strtolower($this->slugger->slug($title)))
                ->setUpdatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setCategory($this->getReference($faker->randomElement($categories)))
                ->setCreatedAt(\DateTimeImmutable::createFromMutable($faker->dateTime()))
                ->setUser($this->getReference('user'. $faker->numberBetween(1, 10)))
                ->setContent($faker->paragraph(10))
                ->setDuration($faker->numberBetween(2, 60));
            $manager->persist($recipe);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
