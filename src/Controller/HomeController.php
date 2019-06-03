<?php

namespace App\Controller;

use App\Repository\PropertyRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;//il a accès au container qui est l'objet de tous les services de sf y compris twig

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(PropertyRepository $repo)
    {
        $properties = $repo->findLatest();
        
        return $this->render('home/index.html.twig', [
            'properties' => $properties
        ]);
    }
}
