<?php

namespace Aimeos\Aimeos\EventListener;

use TYPO3\CMS\Core\Attribute\AsEventListener;


#[AsEventListener(identifier: 'aimeos/clear-cache')]
class ClearCacheListener
{
    public function __invoke(\TYPO3\CMS\Core\Cache\Event\CacheFlushEvent $event): void
    {
        if ((bool) \Aimeos\Aimeos\Base::getExtConfig('useAPC', false) === true
            && function_exists('apcu_clear_cache')
        ) {
            apcu_clear_cache();
        }
    }
}
