<?php

namespace App\Form;

use App\Entity\Drink;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class DrinkType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder = new DynamicFormBuilder($builder);
        $builder
            ->add('teaOrCoffee', ChoiceType::class, [
                'choices' => [
                    'Tea' => 'tea',
                    'Coffee' => 'coffee',
                ],
                'expanded' => false,
                'multiple' => false,
                'autocomplete' => true,
            ])
            ->addDependent('teaType', 'teaOrCoffee', function (DependentField $field, ?string $teaOrCoffee) {
                if ($teaOrCoffee ==='tea'){
                    $field->add(ChoiceType::class, [
                        'choices' => [
                            'Black tea' => 'black',
                            'Green tea' => 'green',
                            'Herbal tea' => 'herbal',
                        ],
                        'expanded' => false,
                        'multiple' => false,
                        'autocomplete' => true,
                    ]);
                }
            })
            ->addDependent('coffeeType', 'teaOrCoffee', function (DependentField $field, ?string $coffeeType) {
                if ($coffeeType ==='coffee'){
                    $field->add(ChoiceType::class, [
                        'choices' => [
                            'Espresso' => 'espresso',
                            'Cappuccino' => 'cappuccino',
                            'Americano' => 'americano',
                        ],
                        'expanded' => false,
                        'multiple' => false,
                        'autocomplete' => true,
                    ]);
                }
            })

            ->add('sugar', ChoiceType::class, [
                'choices' => [
                    'Sugar' => true,
                    'No sugar' => false,
                ],
                'expanded' => true,
                'multiple' => false,
                'autocomplete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Drink::class,
        ]);
    }
}
