<?php

namespace AppBundle\Security\Authorization;

use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

//------------------------------------------------------------------------------

use AppBundle\Entity\User;

//------------------------------------------------------------------------------


class UserVoter extends Voter
{
    const ROLE_ADMIN = 'ROLE_ADMIN';

    public function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        if (self::ROLE_ADMIN === $attribute && in_array($attribute, $token->getUser()->getRoles())) {
            return true;
        }

        return false;
    }

    public function supports($attribute, $subject)
    {
        if ($subject instanceof User) {
            if ($attribute != self::ROLE_ADMIN) {
                return false;
            }

            return true;
        }
    }
}