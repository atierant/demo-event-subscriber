<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // when@prod:
    // As of Symfony 5.1, deprecations are logged in the dedicated "deprecation" channel when it exists
    // monolog:
    //    channels: [deprecation]
    //    handlers:
    //        deprecation:
    //            type: stream
    //            channels: [deprecation]
    //            path: php://stderr
};
