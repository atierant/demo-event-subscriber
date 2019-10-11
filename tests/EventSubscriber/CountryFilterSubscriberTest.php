<?php
declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use Doctrine\Common\Annotations\Reader;
use Doctrine\ORM\EntityManagerInterface;
use App\EventSubscriber\CountryFilterSubscriber;
use PHPUnit\Framework\TestCase;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class TreatmentSiteFilterSubscriberTest
 */
class CountryFilterSubscriberTest extends TestCase
{
    /** @var EventSubscriberInterface */
    private $subscriber;
    /** @var EventDispatcherInterface */
    private $dispatcher;
    /** @var Request */
    private $request;

    /**
     * @return GetResponseEvent
     */
    private function createEvent()
    {
        return new RequestEvent(
            $this->createMock(HttpKernelInterface::class),
            $this->request,
            HttpKernelInterface::MASTER_REQUEST
        );
    }

    /**
     *
     */
    protected function setUp()
    {
        $this->subscriber = new CountryFilterSubscriber(
            $this->createMock(EntityManagerInterface::class),
            $this->createMock(TokenStorageInterface::class),
            $this->createMock(Reader::class)
        );
        $this->dispatcher = new EventDispatcher();
        $this->dispatcher->addSubscriber($this->subscriber);
        $this->request = Request::create('/fr/blog');
    }

    public function testListener()
    {
        $event = $this->dispatcher->dispatch($this->createEvent(), KernelEvents::REQUEST);
        self::assertInstanceOf(RequestEvent::class, $event);
    }
}
