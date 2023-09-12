<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 13/12/22
 * Time: 20:31
 */

namespace Shokhaa\RequestResolverBundle\Exception;


use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;

class LogicException extends DomainException implements JsonExceptionInterface
{
    public function __construct(string $message, int $code = 400)
    {
        parent::__construct($message, $code);
    }

    public function response(): JsonResponse
    {
        return new JsonResponse([
            'error' => [
                'code'    => $this->getCode(),
                'message' => $this->getMessage()
            ]
        ], 400);
    }
}
