<?php


class UserProfileDAOTest extends \Codeception\Test\Unit
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
    public function testRemoveProfile()
    {
        $dbStub = $this->createMock(\Doctrine\DBAL\Connection::class);
        $dbStub->expects($this->once())
            ->method('delete')
            ->with($this->equalTo('user_profile'), $this->equalTo(['id'=>1]));

        $dao = new \Javiern\DAO\UserProfile($dbStub);
        $dao->removeUserProfile(1);
    }

    public function testSaveUserProfile()
    {
        $dbStub = $this->createMock(\Doctrine\DBAL\Connection::class);
        $dbStub->expects($this->once())
            ->method('update')
            ->with(
                $this->equalTo('user_profile'),
                $this->equalTo([
                    'id' => 1,
                    'name' => 'a',
                    'address' => 'b',
                ]),
                $this->equalTo([
                    'id' => 1,
                ])
            );

        $dao = new \Javiern\DAO\UserProfile($dbStub);
        $dao->saveUserProfile([
            'id' => 1,
            'name' => 'a',
            'address' => 'b',
        ]);
    }

    public function testNewUserProfile()
    {
        $dbStub = $this->createMock(\Doctrine\DBAL\Connection::class);
        $dbStub->expects($this->once())
            ->method('insert')
            ->with(
                $this->equalTo('user_profile'),
                $this->equalTo([
                    'name' => 'a',
                    'address' => 'b',
                ])
            );

        $dao = new \Javiern\DAO\UserProfile($dbStub);
        $dao->newUserProfile([
            'name' => 'a',
            'address' => 'b',
        ]);
    }

    public function testGetUsetProfile()
    {
        $stmtStub = $this->createMock(Doctrine\DBAL\Driver\Statement::class);
        $stmtStub->expects($this->once())->method('bindValue')
            ->with($this->equalTo('id'), $this->equalTo(1), $this->equalTo(null));

        $dbStub = $this->createMock(\Doctrine\DBAL\Connection::class);
        $dbStub->expects($this->once())
            ->method('prepare')
            ->willReturn($stmtStub);

        $dao = new \Javiern\DAO\UserProfile($dbStub);
        $dao->getUserProfile(1);
    }
}