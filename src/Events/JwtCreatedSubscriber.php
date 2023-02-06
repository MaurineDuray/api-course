<?php

namespace App\Events;

use Lexik\Bundle\JWTAuthenticationBundle\Event\JWTCreatedEvent;

class JwtCreatedSubscriber
{
    public function updateJwtData(JWTCreatedEvent $event)
    {
        // récupe l'utilisateur pour avoir firstname et lastname
        $user = $event->getUser();
        $data = $event->getData(); //tableau contenant les données de base sur le user en jwt

        $data['firstName'] = $user->getFirstName();
        $data['lastName'] = $user->getLastName();

        $event->setData($data); //On donne le new tab de données
    }
}