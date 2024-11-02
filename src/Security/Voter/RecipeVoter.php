<?php

namespace App\Security\Voter;

use App\Entity\Recipe;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class RecipeVoter extends Voter
{
//    public function __construct(private readonly Security $security)
//    {
//    }

    public const EDIT = 'RECIPE_EDIT';
    public const VIEW = 'RECIPE_VIEW';
    public const CREATE = 'RECIPE_CREATE';
    public const LIST = 'RECIPE_LIST';
    public const LIST_ALL = 'RECIPE_ALL';

    /**
     * @param string $attribute
     * @param Recipe|null $subject
     * @return bool
     */

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::CREATE, self::LIST, self::LIST_ALL]) || ( in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Recipe );
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof User) {
            return false;
        }


        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                return $subject->getUser()->getId() === $user->getId();
                // logic to determine if the user can EDIT
                // return true or false
                break;

            case self::LIST_ALL:
//                return $this->security->isGranted('ROLE_ADMIN');
            case self::VIEW:
            case self::LIST:
            case self::CREATE:
                return true;
//                return $this->security->isGranted('ROLE_USER', $user);
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
    }
}
