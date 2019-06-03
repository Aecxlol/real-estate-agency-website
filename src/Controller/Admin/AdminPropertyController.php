<?php

namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminPropertyController extends AbstractController
{
    /** 
     * @var PropertyRepository
    */
    private $repository;
    /** 
     * @var ObjectManager
    */
    private $manager;

    public function __construct(PropertyRepository $repository, ObjectManager $manager)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    /** 
     * @Route("/admin", name="admin_property_index")
    */
    public function index()
    {
        $properties = $this->repository->findAll();

        return $this->render('admin/property/index.html.twig', [
            'properties' => $properties
        ]);
    }

    /** 
     * @Route("/admin/property/create", name="admin_property_create")
    */
    public function new(Request $request)
    {
        //on crée un bien vide comme on a pas d'info de la DB
        $property = new Property();

        $form = $this->createForm(PropertyType::class, $property);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            //Cette fois-ci on use persist car notre property étant créée de manière manuelle, elle n'est pas suivie pas notre entité manager
            //donc faut lui dire qu'avant de flush il faut persist comme ça elle sera traquée par l'entity mùanager
            $this->manager->persist($property);
            $this->manager->flush();
            $this->addFlash('success', 'Le bien a bien été ajouté');
            return $this->redirectToRoute('admin_property_index');
        }

        return $this->render('admin/property/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //on accepte que les method de stype get et post pour l'adresse de cette route
    /** 
     * @Route("/admin/property/{id}", name="admin_property_edit", methods="GET||POST")
    */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property);

        //voit si la requête est de type post ou get et il va use les setter de notre entité property pour soumettre les champs de notre form
        $form->handleRequest($request);

        //isValid() prend en compte nos Assert dans l'entité
        if($form->isSubmitted() && $form->isValid())
        {
            $this->manager->flush();
            $this->addFlash('success', 'Le bien a bien été édité');
            return $this->redirectToRoute('admin_property_index');
        }

        return $this->render('admin/property/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /** 
     * @Route("/admin/property/{id}", name="admin_property_delete", methods="DELETE")
    */
    public function delete(Property $property, Request $request)
    {
        if($this->isCsrfTokenValid('delete'.$property->getId(), $request->get('_token')))
        {
            $this->manager->remove($property);
            $this->manager->flush();
            $this->addFlash('success', 'Le bien a bien été supprimé');
        }
        return $this->redirectToRoute('admin_property_index');
    }

}