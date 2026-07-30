<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Psr7\Response;

class Testمقررات extends TestCase
{
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(\PDO::class);
    }

    public function testGetمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1]));

        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'مقررات 1']]);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM مقررات WHERE id = :id'))
            ->willReturn($mockStatement);

        $مقرراتController = new مقرراتController($this->mockPDO);
        $result = $مقرراتController->getمقررات($request, $response, ['id' => 1]);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testPostمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مقررات 2']);

        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':name' => 'مقررات 2']));

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO مقررات (name) VALUES (:name)'))
            ->willReturn($mockStatement);

        $مقرراتController = new مقرراتController($this->mockPDO);
        $result = $مقرراتController->postمقررات($request, $response);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(201, $result->getStatusCode());
    }

    public function testPutمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مقررات 3']);

        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1, ':name' => 'مقررات 3']));

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE مقررات SET name = :name WHERE id = :id'))
            ->willReturn($mockStatement);

        $مقرراتController = new مقرراتController($this->mockPDO);
        $result = $مقرراتController->putمقررات($request, $response, ['id' => 1]);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(200, $result->getStatusCode());
    }

    public function testDeleteمقررات()
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $response = $this->createMock(ResponseInterface::class);

        $mockStatement = $this->createMock(\PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo([':id' => 1]));

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM مقررات WHERE id = :id'))
            ->willReturn($mockStatement);

        $مقرراتController = new مقرراتController($this->mockPDO);
        $result = $مقرراتController->deleteمقررات($request, $response, ['id' => 1]);

        $this->assertInstanceOf(ResponseInterface::class, $result);
        $this->assertEquals(204, $result->getStatusCode());
    }
}