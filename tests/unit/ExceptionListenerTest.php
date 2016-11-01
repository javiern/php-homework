<?php


class ExceptionListenerTest extends \Codeception\Test\Unit
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

    private function eventFactory(\Exception $e, $httpCode) {
        $event = $this->createMock(\Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent::class);
        $event->expects($this->once())
            ->method('getException')
            ->willReturn($e);
        $event->expects($this->once())
            ->method('setResponse')
            ->with($this->callback(function(\Symfony\Component\HttpFoundation\Response $response) use ($e, $httpCode){
                return $response->getStatusCode() == $httpCode &&
                    json_decode($response->getContent(), true) == ['message' => $e->getMessage(), 'code' => $e->getCode()]
                    ;
            }));
        return $event;
    }

    public function kernelExceptionDataProvider()
    {

        return [
            [$this->eventFactory(new \Exception("test",1), 500)],
            [$this->eventFactory(new \Symfony\Component\HttpKernel\Exception\NotFoundHttpException("test", null, 1), 404)],
            [$this->eventFactory(new \Symfony\Component\HttpKernel\Exception\BadRequestHttpException("test", null, 1), 400)]
        ];
    }

    /**
     * @dataProvider kernelExceptionDataProvider
     */
    public function testOnKernelException($event)
    {
        $listener = new \Javiern\EventListener\ExceptionListener();

        $listener->onKernelException($event);

    }
}