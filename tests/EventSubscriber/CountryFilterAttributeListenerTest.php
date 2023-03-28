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

namespace App\Tests\EventSubscriber;

use App\EventSubscriber\CountryFilterAttributeListener;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TreatmentSiteFilterSubscriberTest.
 */
class CountryFilterAttributeListenerTest extends TestCase
{
    private EventDispatcherInterface $dispatcher;
    private Request $request;

    /**
     * @return RequestEvent
     */
    private function createEvent()
    {
        return new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->request,
            HttpKernelInterface::MAIN_REQUEST
        );
    }

    protected function setUp(): void
    {
        $subscriber = new CountryFilterAttributeListener(
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(TokenStorageInterface::class),
        );
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($subscriber);
        $this->request = Request::create('/fr/blog');
    }

    public function testListener(): void
    {
        $event = $this->dispatcher->dispatch($this->createEvent(), KernelEvents::REQUEST);
        self::assertInstanceOf(RequestEvent::class, $event);
    }
}
