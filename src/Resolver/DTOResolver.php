<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 14/12/22
 * Time: 17:21
 */

namespace Shokhaa\RequestResolverBundle\Resolver;


use Shokhaa\RequestResolverBundle\Exception\ValidationException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DTOResolver implements ValueResolverInterface
{

    public function __construct(private readonly DenormalizerInterface $denormalize,
                                private readonly ValidatorInterface    $validator)
    {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {

        $argumentType = $argument->getType();
        if (!$argumentType || !is_subclass_of($argumentType, RequestInterface::class)) {
            return [];
        }

        if (!$data = $this->getBody($request)) {
            return [];
        }

        $dto = $this->denormalize->denormalize($data, $argument->getType(), context: [
            AbstractObjectNormalizer::DISABLE_TYPE_ENFORCEMENT => true,
        ]);
        $this->assertDTOIsValid($dto);

        yield $dto;
    }


    public function getBody(Request $request): array|false
    {
        if ($request->getContentTypeFormat() === 'json' && $request->getContent() !== '') {
            return json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        }
        if ($request->isMethod('POST')) {
            return $request->request->all();
        }
        if ($request->isMethod('GET') && $request->getQueryString()) {
            return $request->query->all();
        }
        return false;

    }

    private function assertDTOIsValid(RequestInterface $request): void
    {
        $errors = $this->validator->validate($request);
        if ($errors->count() > 0) {
            throw new ValidationException($errors);
        }
    }
}
