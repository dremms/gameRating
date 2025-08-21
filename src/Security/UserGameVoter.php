<?php

// src/Security/UserGameVoter.php
namespace App\Security;

use App\Entity\UserGame;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserGameVoter extends Voter
{
    public const EDIT = 'USERGAME_EDIT';
    public const DELETE = 'USERGAME_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE], true)
            && $subject instanceof UserGame;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user) {
            return false;
        }

        /** @var UserGame $userGame */
        $userGame = $subject;

        return match ($attribute) {
            self::EDIT, self::DELETE => $userGame->getUser() === $user,
            default => false,
        };
    }
}
