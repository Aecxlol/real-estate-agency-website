<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;//classe pour les erreurs

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     */
    public function login(AuthenticationUtils $authenticationUtils)
    {
        //Récupère les erreurs liées aux co
        $error = $authenticationUtils->getLastAuthenticationError();

        //permet de récup le dernier nom d'user tapé par le user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'lastUsername' => $lastUsername,
            'error' => $error
        ]);
    }

    /** 
     * @Route("/logout", name="logout")
    */
    public function logout()
    {
        
    }
}
