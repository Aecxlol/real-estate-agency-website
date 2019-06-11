<?php

namespace App\Listener;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PreFlushEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;//permet de manipuler les infos en cache
use Doctrine\ORM\Event\LifecycleEventArgs;

class ImageCacheSubscriber implements EventSubscriber
{
    /** 
     * @var CacheManager
    */
    private $cacheManager;

    /** 
     * @var UploaderHelper
    */
    private $uploaderHelper;

    public function __construct(CacheManager $cacheManager, UploaderHelper $uploaderHelper)
    {
        $this->cacheManager = $cacheManager;
        $this->uploaderHelper = $uploaderHelper;
    }
    
    //fonction qui va retourner les éléments qu'on va écouter
    //preRemove -> écoute quand une entité est delete
    //preUpdate -> est modifiée
    public function getSubscribedEvents()
    {
        return [
            'preRemove',
            'preUpdate'
        ];
    }

    public function preRemove(LifecycleEventArgs $args)
    {
        $entity = $args->getEntity();

        if(!$entity instanceof Property)
        {
            return;
        }
        //Pas besoin de vérifier si y a une image uploadé comme on veut supprimer la property dans tous les cas
        $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
    }

    //avant chaque update de notre entité, cette méthode sera appelée
    public function preUpdate(PreUpdateEventArgs $args)
    {
        $entity = $args->getEntity();
        dump($entity);
        //On peut tester de faire un dump de notre entité lorsque l'ont fait une update d'une property
        //Si mon entité n'est pas une instance de Property alors je retourne
        if(!$entity instanceof Property)
        {
            return;
        }
        //si mon entité est une instance de UploadedFile fonc est uploadé
        if($entity->getImageFile() instanceof UploadedFile)
        {
            //alors je remove le fichier qui lui était associé
            $this->cacheManager->remove($this->uploaderHelper->asset($entity, 'imageFile'));
        }
    }
}