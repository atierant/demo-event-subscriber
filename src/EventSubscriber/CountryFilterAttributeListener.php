<?php

declare(strict_types=1);

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class CountryFilterAttributeListener.
 *
 * @see http://blog.mthomas.fr/2016/12/07/mettre-en-place-un-filter-avec-doctrine/
 */
final class CountryFilterAttributeListener implements EventSubscriberInterface
{
    /**
     * CountryFilterAttributeListener constructor.
     */
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly TokenStorageInterface $tokenStorage
    ) {
    }

    /**
     * Récupère l'utilisateur courant de la requête.
     */
    private function getUser(): UserInterface|null
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }
        $user = $token->getUser();

        return $user instanceof UserInterface ? $user : null;
    }

    /**
     * Listener qui se déclenche à chaque Request du Kernel.
     *
     * @see https://symfony.com/doc/3.4/reference/events.html
     * @see https://symfony.com/doc/3.4/event_dispatcher.html#creating-an-event-subscriber
     */
    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 0]]];
    }

    /**
     * Sur les routes d'affichage des articles du blog,
     * Si l'utilisateur n'a pas un rôle particulier ('ROLE_INTERNATIONAL'),
     * alors on va filtrer toutes ses requêtes par la langue de son profil.
     *
     * @throws \Exception
     */
    public function onKernelRequest(RequestEvent $requestEvent): void
    {
        if (!\in_array($requestEvent->getRequest()->attributes->get('_route'), [
            'homepage',
            'blog_index',
            'blog_index_paginated',
        ], true)) {
            return;
        }

        $user = $this->getUser();
        if (
            !$user instanceof User
            || \in_array(User::ROLE_ADMIN, $user->getRoles(), true)
            || \in_array(User::ROLE_INTERNATIONAL, $user->getRoles(), true)
        ) {
            return;
        }

        // L'utilisateur a un profil standard, on filtre alors par la langue de son profil
        $filter = $this->entityManager->getFilters()->enable('country_filter');
        $filter->setParameter('country', $user->getCountry());
    }
}
