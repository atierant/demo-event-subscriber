<?php

declare(strict_types=1);

use App\Entity\User;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

return static function (ContainerConfigurator $containerConfigurator): void {
    $containerConfigurator->extension('security', [
        'password_hashers' => [
            PasswordAuthenticatedUserInterface::class => 'auto',
        ],
        'providers' => [
            'database_users' => [
                'entity' => [
                    'class' => User::class,
                    'property' => 'username',
                ],
            ],
        ],
        'firewalls' => [
            'dev' => [
                'pattern' => '^/(_(profiler|wdt)|css|images|js)/',
                'security' => false,
            ],
            'main' => [
                'lazy' => true,
                'provider' => 'database_users',
                'form_login' => [
                    'check_path' => 'security_login',
                    'login_path' => 'security_login',
                    'enable_csrf' => true,
                    'default_target_path' => 'blog_index',
                ],
                'remember_me' => [
                    'secret' => '%kernel.secret%',
                    'lifetime' => 604800,
                ],
                'logout' => [
                    'path' => 'security_logout',
                    'target' => 'homepage',
                    'enable_csrf' => true,
                ],
                'entry_point' => 'form_login',
            ],
        ],
        'access_control' => [
            [
                'path' => '^/(%app_locales%)/admin',
                'roles' => 'ROLE_ADMIN',
            ],
        ],
        'role_hierarchy' => [
            'ROLE_ADMIN' => 'ROLE_USER',
        ],
    ]);
    if ($containerConfigurator->env() === 'test') {
        $containerConfigurator->extension('security', [
            'password_hashers' => [
                PasswordAuthenticatedUserInterface::class => [
                    'algorithm' => 'auto',
                    'cost' => 4,
                    'time_cost' => 3,
                    'memory_cost' => 10,
                ],
            ],
            'firewalls' => [
                'main' => [
                    'http_basic' => null,
                ],
            ],
        ]);
    }
};
