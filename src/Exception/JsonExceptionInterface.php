<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 14/12/22
 * Time: 18:41
 */

namespace Shokhaa\RequestResolverBundle\Exception;

use Symfony\Component\HttpFoundation\JsonResponse;

interface JsonExceptionInterface
{

    public function response(): JsonResponse;
}
