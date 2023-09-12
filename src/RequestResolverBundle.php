<?php
/**
 * Created by PhpStorm.
 * User: Shokhaa
 * Date: 12/09/23
 * Time: 16:27
 */
namespace Shokhaa\RequestResolverBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class RequestResolverBundle extends Bundle
{

    public function getPath(): string
    {
        return dirname(__DIR__);
    }


}