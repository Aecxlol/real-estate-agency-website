<?php

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PropertyController extends AbstractController
{
    //On précise que c'est un var de type instance de PropertyRepository
    /** 
     * @var PropertyRepository
     */
    private $repository;
    /** 
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager, PropertyRepository $repo)
    {
        $this->repository = $repo;
        $this->manager = $manager;
    }

    /**
     * @Route("/propertieslist", name="property_index")
     */
    public function index()
    {
        $properties = $this->repository->findAllVisible();//utilisation  de notre fonction créée dans le repo

        return $this->render('property/index.html.twig', [
            'current_item' => 'properties',
            'properties' => $properties
        ]);
    }

    /** 
     * @Route("/properties/{slug}/{id}", name="property_show", requirements={"slug": "[a-z0-9\-]*"})
    */
    public function show(Property $property, string $slug)
    {
        if($property->getSlug() !== $slug)
        {
            return $this->redirectToRoute('property_show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301
        );
        }

        return $this->render('property/show.html.twig', [
            'current_item' => 'properties',
            'property' => $property
        ]);
    }
}
