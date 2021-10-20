<?php

use Symfony\Component\Translation\MessageCatalogue;

$catalogue = new MessageCatalogue('es-MX', array (
));

$catalogueEs = new MessageCatalogue('es', array (
));
$catalogue->addFallbackCatalogue($catalogueEs);

return $catalogue;
