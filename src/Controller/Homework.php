<?php
namespace Javiern\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class Homework extends ContainerAwareController
{
    public function homepage(Request $request)
    {
        return new JsonResponse(['hello'=>'world']);
    }
}