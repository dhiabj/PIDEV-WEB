<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccountExpiredException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker extends AbstractController implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if ($user->getEtat() == "Not Verified") {
            $this->addFlash('error',"Votre compte n'est pas encore vérifier !");
            throw new AccountExpiredException("Votre compte n'est pas encore vérifier !");
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
}