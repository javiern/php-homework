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

    /**
     * @return null|\Symfony\Component\Routing\RouterInterface
     */
    public function getRouter()
    {
        if ($this->container->has('router')) {
            return $this->container->get('router');
        }

        return null;
    }
}
