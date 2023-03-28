<?php

declare(strict_types=1);

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $containerConfigurator): void {
    // @see https://symfony.com/doc/current/reference/configuration/framework.html
    $containerConfigurator->extension('framework', [
        'secret' => '%env(APP_SECRET)%',
        'csrf_protection' => true,
        'http_method_override' => false,
        'handle_all_throwables' => true,

        // Enables session support. Note that the session will ONLY be started if you read or write from it.
        // Remove or comment this section to explicitly disable session support.
        'session' => [
            'handler_id' => null,
            'cookie_secure' => 'auto',
            'cookie_samesite' => 'lax',
            'storage_factory_id' => 'session.storage.factory.native',
        ],

        // When using the HTTP Cache, ESI allows to render page fragments separately
        // and with different cache configurations for each fragment
        // https://symfony.com/doc/current/http_cache/esi.html
        'esi' => true,
        'fragments' => true,
        'php_errors' => [
            'log' => true,
        ],

        // The 'ide' option turns all of the file paths in an exception page
        // into clickable links that open the given file using your favorite IDE.
        // When 'ide' is set to null the file is opened in your web browser.
        // @see https://symfony.com/doc/current/reference/configuration/framework.html#ide
        'ide' => null,
    ]);

    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('framework', [
            'test' => true,
            'session' => [
                'storage_factory_id' => 'session.storage.factory.mock_file',
            ],
        ]);
    }
};
