<?php


class UserProfileControllerTest extends \Codeception\Test\Unit
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
    public function testGetProfile()
    {
        $daoStub = $this->createMock(\Javiern\DAO\UserProfile::class);
        $daoStub->expects($this->once())
            ->method('getUserProfile')
            ->with($this->equalTo(1))
            ->willReturn(['id' => 1, 'name' => 'a']);

        $diStub = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $diStub->expects($this->once())
            ->method('get')
            ->willReturn($daoStub);

        $requestStub = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);

        $controller = new \Javiern\Controller\UserProfile();
        $controller->setContainer($diStub);

        $response = $controller->getProfile($requestStub, 1);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals(json_decode($response->getContent(),true), ['id' => 1, 'name' => 'a']);

    }

    public function testGetProfileError()
    {
        $daoStub = $this->createMock(\Javiern\DAO\UserProfile::class);
        $daoStub->expects($this->once())
            ->method('getUserProfile')
            ->with($this->equalTo(1))
            ->willReturn(false);

        $diStub = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $diStub->expects($this->once())
            ->method('get')
            ->willReturn($daoStub);

        $requestStub = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);

        $controller = new \Javiern\Controller\UserProfile();
        $controller->setContainer($diStub);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
        $response = $controller->getProfile($requestStub, 1);
    }

    public function testRemoveProfile()
    {
        $daoStub = $this->createMock(\Javiern\DAO\UserProfile::class);
        $daoStub->expects($this->once())
            ->method('getUserProfile')
            ->with($this->equalTo(1))
            ->willReturn(['id' => 1, 'name' => 'a']);

        $daoStub->expects($this->once())
            ->method('removeUserProfile')
            ->with($this->equalTo(1))
            ->willReturn(true);

        $diStub = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $diStub->expects($this->once())
            ->method('get')
            ->willReturn($daoStub);

        $requestStub = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);

        $controller = new \Javiern\Controller\UserProfile();
        $controller->setContainer($diStub);

        $response = $controller->remove($requestStub, 1);

        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testRemoveProfileError()
    {
        $daoStub = $this->createMock(\Javiern\DAO\UserProfile::class);
        $daoStub->expects($this->once())
            ->method('getUserProfile')
            ->with($this->equalTo(1))
            ->willReturn(false);

        $diStub = $this->createMock(\Symfony\Component\DependencyInjection\ContainerInterface::class);
        $diStub->expects($this->once())
            ->method('get')
            ->willReturn($daoStub);

        $requestStub = $this->createMock(\Symfony\Component\HttpFoundation\Request::class);

        $controller = new \Javiern\Controller\UserProfile();
        $controller->setContainer($diStub);

        $this->expectException(\Symfony\Component\HttpKernel\Exception\NotFoundHttpException::class);
        $response = $controller->remove($requestStub, 1);
    }


}