<?php

use \Codeception\Util\Stub;

class UserProfilerValidationServiceTest extends \Codeception\Test\Unit
{
    /**
     * @var \HomeworkTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    // tests
    public function testConstructor()
    {
        $validator = Stub::makeEmpty('Symfony\Component\Validator\Validator\ValidatorInterface');
        $service = new \Javiern\Services\UserProfileValidationService($validator);
        $this->assertSame($validator, $service->getValidator());
    }

    public function validateEditParameterProvider()
    {
        return [
            [['id'=> 1, 'name' => 'a', 'address' => 'b'], ['id'=> 1, 'name' => 'aedit', 'address' => 'bedit'], []],
            [['id'=> 1, 'name' => 'a', 'address' => 'b'], ['id'=> 3, 'name' => 'aedit', 'address' => 'bedit'], ['id']],
            [[],[],['id']]
        ];
    }
    /**
     * @dataProvider validateEditParameterProvider
     */
    public function testValidateEdit($original, $modified, $expectedFieldWithErrors) {
        $validatorStub = $this->createMock(\Symfony\Component\Validator\Validator\RecursiveValidator::class);
        $validatorStub->expects($this->any())->method('validate')->withConsecutive(
            [array_key_exists('name', $modified) ? $modified['name'] : null],
            [array_key_exists('address', $modified) ? $modified['address'] : null]
        );

        $service = new \Javiern\Services\UserProfileValidationService($validatorStub);
        $result = $service->validateEdit($original, $modified);

        $this->assertEquals($expectedFieldWithErrors, array_keys($result));
    }


    public function validateParameterProvider()
    {
        return [
            [['name' => 'Javier Neyra', 'address' => 'Some address']],
            [['name' => null, 'address' => 'Some address']],
            [['name' => '', 'address' => 'Some address']],
            [['address' => 'Some address']],
            [['name' => 'Javier Neyra', 'address' => null]],
            [['name' => 'Javier Neyra', 'address' => '']],
            [['name' => 'Javier Neyra']],
            [[]]
        ];
    }

    /**
     * @dataProvider validateParameterProvider
     */
    public function testValidateParameterPassing($data)
    {
        $validatorStub = $this->createMock(\Symfony\Component\Validator\Validator\RecursiveValidator::class);

        $validatorStub->expects($this->exactly(2))->method('validate')->withConsecutive(
            [array_key_exists('name', $data) ? $data['name'] : null],
            [array_key_exists('address', $data) ? $data['address'] : null]
        );

        $service = new \Javiern\Services\UserProfileValidationService($validatorStub);
        $service->validate($data);
    }

    public function testValidateResult()
    {
        $violationListStub = $this->createMock(\Symfony\Component\Validator\ConstraintViolationList::class);
        $violationStub = $this->createMock(\Symfony\Component\Validator\ConstraintViolation::class);
        $violationStub->expects($this->any())->method('getMessage')->willReturn("this is the message");
        $violations = [
            $violationStub
        ];

        $violationListStub->expects($this->any())->method('getIterator')->willReturn(new ArrayIterator($violations));
        $violationListStub->expects($this->any())->method('count')->willReturn(1);

        $validatorStub = $this->createMock(\Symfony\Component\Validator\Validator\RecursiveValidator::class);
        $validatorStub->expects($this->any())->method('validate')
            ->willReturn($violationListStub)
        ;

        $service = new \Javiern\Services\UserProfileValidationService($validatorStub);
        $result = $service->validate([]);
        $this->assertEquals("this is the message", $result['name'][0]);
        $this->assertEquals("this is the message", $result['address'][0]);
    }
}