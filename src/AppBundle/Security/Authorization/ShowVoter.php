<?php

namespace AppBundle\Security\Authorization;

use AppBundle\Entity\Show;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ShowVoter extends Voter
{
	public function voteOnAttribute($attribute, $subject, TokenInterface $token)
	{
		// Récupérer l'utilisateur connecté (ou authentifié) => $user
		$user = $token->getUser();

		// Récupérer le show => $show
		$show = $subject;

		// Si l'utilisateur est l'auteur du show, il peut faire quelque chose.
		// Si $show->getAuthor() === $user ====> return true
		if ($show->getAuthor() === $user) {
			return true;
		}

		// Sinon, l'utilisateur n'a pas le droit.
		// Sinon, return false
		return false;
	}

	public function supports($attribute, $subject)
	{
		if ($subject instanceof Show) {
			return true;
		}

		return false;
	}
}