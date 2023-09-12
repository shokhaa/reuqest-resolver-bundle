<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 13/12/22
 * Time: 20:32
 */

namespace Shokhaa\RequestResolverBundle\Exception;

use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class ApiExceptionFormatter implements EventSubscriberInterface
{

    public function __construct(private readonly LoggerInterface $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException'
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {

        $exception = $event->getThrowable();
        $request = $event->getRequest();

        if ($request->headers->get('Content-Type') === 'application/json' and $exception instanceof JsonExceptionInterface){
            $this->logger->warning($exception->getMessage(), ['exception' => $exception]);
            $event->setResponse($exception->response());
        }

    }
}
