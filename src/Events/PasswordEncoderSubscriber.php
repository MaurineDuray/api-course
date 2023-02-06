<?php

namespace App\Events;

use App\Entity\User;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Symfony\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoderSubscriber implements EventSubscriberInterface
{

    private $encoder;

    public function __construct(UserPasswordHasherInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::VIEW =>['encoderPassword',EventPriorities::PRE_WRITE]
            // On intervient avant de l'inscrire dans la bdd, et donc nous n'avons pas besoin de manager
        ];
    }

    public function encoderPassword(ViewEvent $event)
    {
        $user = $event->getControllerResult(); //Récupérat° de l'objet désérialisé

        $method = $event->getRequest()->getMethod(); //récup la méthode

        //vérifier quand la requête envoie un user et qu'elle est de type post
        if($user instanceof User && $method==="POST")
        {
            $hash = $this->encoder->hashPassword($user, $user->getPassword());
            $user->setPassword($hash);
        }
    }
}