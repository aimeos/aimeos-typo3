<?php

namespace Aimeos\Aimeos\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;


#[AsEventListener(identifier: 'aimeos/logout')]
class LogoutListener
{
    public function __invoke(\TYPO3\CMS\FrontendLogin\Event\LogoutConfirmedEvent $event): void
    {
        \Aimeos\Aimeos\Base::logout();
    }
}
