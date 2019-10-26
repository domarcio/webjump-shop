<?php

declare(strict_types=1);

namespace Nogues\Common\Container;

use Doctrine\Common\Cache\ArrayCache;

use Interop\Container\ContainerInterface;

final class DoctrineArrayCacheFactory
{
    public function __invoke(ContainerInterface $container)
    {
        return new ArrayCache();
    }
}
