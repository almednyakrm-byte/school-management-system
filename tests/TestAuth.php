<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use App\Auth;
use App\Database;

class TestAuth extends TestCase
{
    private $auth;
    private $database;

    protected function setUp(): void
    {
        $this->database = $this->createMock(Database::class);
        $this->auth = new Auth($this->database);
    }

    public function testLoginSuccess()
    {
        $username = 'testuser';
        $password = 'testpassword';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn([
                'username' => $username,
                'password' => $password,
            ]);

        $result = $this->auth->login($username, $password);

        $this->assertTrue($result);
        $this->assertEquals($username, $_SESSION['username']);
    }

    public function testLoginFailure()
    {
        $username = 'testuser';
        $password = 'wrongpassword';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn([
                'username' => $username,
                'password' => 'testpassword',
            ]);

        $result = $this->auth->login($username, $password);

        $this->assertFalse($result);
        $this->assertNull($_SESSION['username']);
    }

    public function testRegisterSuccess()
    {
        $username = 'newuser';
        $password = 'newpassword';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn(null);

        $this->database
            ->method('createUser')
            ->with($username, $password)
            ->willReturn(true);

        $result = $this->auth->register($username, $password);

        $this->assertTrue($result);
        $this->assertEquals($username, $_SESSION['username']);
    }

    public function testRegisterFailure()
    {
        $username = 'existinguser';
        $password = 'newpassword';

        $this->database
            ->method('getUser')
            ->with($username)
            ->willReturn([
                'username' => $username,
                'password' => 'existingpassword',
            ]);

        $result = $this->auth->register($username, $password);

        $this->assertFalse($result);
        $this->assertNull($_SESSION['username']);
    }
}