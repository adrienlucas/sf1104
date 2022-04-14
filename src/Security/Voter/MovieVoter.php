<?php

namespace App\Security\Voter;

use App\Entity\Movie;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MovieVoter extends Voter
{
    public const DELETION = 'deletion';

    protected function supports(string $attribute, $subject): bool
    {
        return $attribute === self::DELETION
            && $subject instanceof Movie;
    }

    // @IsGranted("deletion", "movie")
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if($attribute !== self::DELETION || !$subject instanceof Movie) {
            throw new \LogicException('This code should not be reached!');
        }

        return $subject->getCreatedBy() instanceof User &&
            $subject->getCreatedBy()->isEqualTo($user);
    }
}
