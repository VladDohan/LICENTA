<?php
namespace App\EventSubscriber;

use App\Entity\User;
use App\Exception\AccountNotVerifiedException;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;
use Symfony\Component\Security\Http\Event\CheckPassportEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class CheckVerifiedUserSubscriber implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            CheckPassportEvent::class => ['onCheckPassport', -10],
        ];
    }

    public function onCheckPassport(CheckPassportEvent $event): void
    {
        $passport = $event->getPassport();
        $user = $passport->getUser();
        
        if (!$user instanceof User) {
            return;
        }

        if (!$user->isVerified()) {
            throw new AccountNotVerifiedException();
        }
    }
}