<?php

namespace App\Twig\Components;

use App\Form\DrinkType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\ComponentWithFormTrait;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class DrinkFormComponent extends AbstractController
{
    use DefaultActionTrait, ComponentWithFormTrait;

    protected function instantiateForm(): FormInterface
    {
        return $this->createForm(DrinkType::class);
    }
}
