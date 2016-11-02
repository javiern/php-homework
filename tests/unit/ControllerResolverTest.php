<?php


class ControllerResolverTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testInstantiateFromDIController()
    {
        $diStub = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $diStub->expects($this->once())
            ->method('has')
            ->willReturn(true);
        $diStub->expects($this->once())
            ->method('get')
            ->willReturn(new \Javiern\Controller\UserProfile());

        $requestStub = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);
        $requestStub->attributes = new Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag([
            '_controller' => 'Javiern\Controller\UserProfile::getProfile'
        ]);

        $resolver = new \Javiern\Controller\ControllerResolver($diStub);
        $controller = $resolver->getController($requestStub);

        $this->assertEquals([new \Javiern\Controller\UserProfile() , 'getProfile'], $controller);
    }

    public function testInstantiateController()
    {
        $diStub = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $diStub->expects($this->once())
            ->method('has')
            ->willReturn(false);

        $requestStub = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);
        $requestStub->attributes = new Symfony\Component\DependencyInjection\ParameterBag\FrozenParameterBag([
            '_controller' => 'Javiern\Controller\UserProfile::getProfile'
        ]);

        $resolver = new \Javiern\Controller\ControllerResolver($diStub);
        $controller = $resolver->getController($requestStub);

        $expectedController = new \Javiern\Controller\UserProfile();
        $expectedController->setContainer($diStub);

        $this->assertEquals([$expectedController , 'getProfile'], $controller);
    }
}