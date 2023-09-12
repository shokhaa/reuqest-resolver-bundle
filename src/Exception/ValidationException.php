<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 14/12/22
 * Time: 18:28
 */
declare(strict_types=1);
namespace Shokhaa\RequestResolverBundle\Exception;

use DomainException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends DomainException implements JsonExceptionInterface
{

    public function __construct(private readonly ConstraintViolationListInterface $violations)
    {
        parent::__construct('Validation error');
    }

    public function response(): JsonResponse
    {
        $validationErrors = [];
        foreach ($this->violations as $violation) {
            $validationErrors[] = [
                'message'     => $violation->getMessage(),
                'input'       => $violation->getPropertyPath(),
                'invalid_value' => $violation->getInvalidValue()
            ];
        }
        return new JsonResponse(['violations' => $validationErrors], 400);
    }
}
