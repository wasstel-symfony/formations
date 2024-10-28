<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{
    #[Route('/', name: 'app_homepage')]
    public function index(EntityManagerInterface $em, UserPasswordHasherInterface $hasher): Response
    {
//        $user = new User();
//        $user->setEmail('admin@admin.com')
//            ->setRoles(['ROLE_ADMIN'])
//            ->setUsername('admin')
//            ->setPassword($hasher->hashPassword($user, 'admin'))
//        ;
//        $em->persist($user);
//        $em->flush();
//        dd($this->getUser());
        return $this->render('homepage/index.html.twig', [
            'controller_name' => 'HomepageController',
        ]);
    }
}
