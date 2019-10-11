<?php
declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class TreatmentSiteContractFilterSubscriber
 *
 * @see http://blog.mthomas.fr/2016/12/07/mettre-en-place-un-filter-avec-doctrine/
 */
class CountryFilterSubscriber implements EventSubscriberInterface
{
    /** @var EntityManagerInterface */
    protected $em;
    /** @var TokenStorageInterface */
    protected $tokenStorage;
    /** @var Reader */
    protected $reader;

    /**
     * ConfiguratorTreatmentSiteContractCheck constructor.
     *
     * @param EntityManagerInterface $em
     * @param TokenStorageInterface  $tokenStorage
     * @param Reader                 $reader
     */
    public function __construct(
        EntityManagerInterface $em,
        TokenStorageInterface $tokenStorage,
        Reader $reader
    ) {
        $this->em = $em;
        $this->tokenStorage = $tokenStorage;
        $this->reader = $reader;
    }

    /**
     * Récupère l'utilisateur courant de la requête
     *
     * @return object|string|null
     */
    private function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if (!$token) {
            return null;
        }
        $user = $token->getUser();

        return $user instanceof UserInterface ? $user : null;
    }

    /**
     * Listener qui se déclenche à chaque Request du Kernel
     *
     * @see https://symfony.com/doc/3.4/reference/events.html
     * @see https://symfony.com/doc/3.4/event_dispatcher.html#creating-an-event-subscriber
     */
    public static function getSubscribedEvents()
    {
        return [KernelEvents::REQUEST => [['onKernelRequest', 0]]];
    }

    /**
     * Sur les routes de récupération des contrats de l'API du backoffice,
     * Si l'utilisateur n'a pas un profil national ('ROLE_NATIONAL'),
     * alors on va filtrer toutes ses requêtes par ses sites de traitement
     *
     * @param RequestEvent $requestEvent
     *
     * @throws Exception
     */
    public function onKernelRequest(RequestEvent $requestEvent)
    {
        if (!in_array($requestEvent->getRequest()->attributes->get('_route'), [
            'homepage',
            'blog_index',
            'blog_index_paginated',
        ])) {
            return;
        }
        $user = $this->getUser();
        if (!$user || !$user instanceof User || in_array('ROLE_INTERNATIONAL', $user->getRoles())) {
            return;
        }

        // L'utilisateur a un profil standard, on filtre alors par tous ses sites de traitement
        $filter = $this->em->getFilters()->enable('country_filter');
        $filter->setParameter('country', $user->getCountry());
        $filter->setAnnotationReader($this->reader);
    }
}
