<?php
namespace Javiern\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ContainerAwareController implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function get($name)
    {
        return $this->container->get($name);
    }

    public function getParameter($name)
    {
        return $this->container->getParameter($name);
    }
}