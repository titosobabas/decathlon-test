<?php

// This file has been auto-generated by the Symfony Dependency Injection Component for internal use.

if (\class_exists(\ContainerUgzik6v\appProdProjectContainer::class, false)) {
    // no-op
} elseif (!include __DIR__.'/ContainerUgzik6v/appProdProjectContainer.php') {
    touch(__DIR__.'/ContainerUgzik6v.legacy');

    return;
}

if (!\class_exists(appProdProjectContainer::class, false)) {
    \class_alias(\ContainerUgzik6v\appProdProjectContainer::class, appProdProjectContainer::class, false);
}

return new \ContainerUgzik6v\appProdProjectContainer([
    'container.build_hash' => 'Ugzik6v',
    'container.build_id' => '83eb0733',
    'container.build_time' => 1634693272,
], __DIR__.\DIRECTORY_SEPARATOR.'ContainerUgzik6v');
