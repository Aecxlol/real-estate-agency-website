<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Repository\PropertyRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(PaginatorInterface $paginator, Request $request)
    {
        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),//utilisation  de notre fonction créée dans le repo,
            $request->query->getInt('page', 1),//si il n'y a pas de page de définie, ce sera la 1 par défaut
            12
        );

        //current_item pour récup la current page du menu
        return $this->render('property/index.html.twig', [
            'current_item' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
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
