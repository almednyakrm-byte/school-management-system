<?php

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use PDO;

class Testمواعيد extends TestCase
{
    private $mockPDO;

    protected function setUp(): void
    {
        $this->mockPDO = $this->createMock(PDO::class);
    }

    public function testGetمواعيد()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $mockStatement->expects($this->once())
            ->method('fetchAll')
            ->willReturn([['id' => 1, 'name' => 'مواعيد']]);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('SELECT * FROM مواعيد WHERE id = :id'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"id":1,"name":"مواعيد"}');

        $مواعيد = new مواعيد($this->mockPDO);
        $result = $مواعيد->getمواعيد($request, $response);

        $this->assertEquals('{"id":1,"name":"مواعيد"}', $result->getBody());
    }

    public function testPostمواعيد()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['name' => 'مواعيد']));

        $mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('INSERT INTO مواعيد (name) VALUES (:name)'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مواعيد']);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"message":"مواعيد created successfully"}');

        $مواعيد = new مواعيد($this->mockPDO);
        $result = $مواعيد->postمواعيد($request, $response);

        $this->assertEquals('{"message":"مواعيد created successfully"}', $result->getBody());
    }

    public function testPutمواعيد()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1, 'name' => 'مواعيد']));

        $mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('UPDATE مواعيد SET name = :name WHERE id = :id'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $request->expects($this->once())
            ->method('getParsedBody')
            ->willReturn(['name' => 'مواعيد']);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"message":"مواعيد updated successfully"}');

        $مواعيد = new مواعيد($this->mockPDO);
        $result = $مواعيد->putمواعيد($request, $response);

        $this->assertEquals('{"message":"مواعيد updated successfully"}', $result->getBody());
    }

    public function testDeleteمواعيد()
    {
        $mockStatement = $this->createMock(PDOStatement::class);
        $mockStatement->expects($this->once())
            ->method('execute')
            ->with($this->equalTo(['id' => 1]));

        $mockStatement->expects($this->once())
            ->method('rowCount')
            ->willReturn(1);

        $this->mockPDO->expects($this->once())
            ->method('prepare')
            ->with($this->equalTo('DELETE FROM مواعيد WHERE id = :id'))
            ->willReturn($mockStatement);

        $request = $this->createMock(ServerRequestInterface::class);
        $request->expects($this->once())
            ->method('getAttribute')
            ->with($this->equalTo('id'))
            ->willReturn(1);

        $response = $this->createMock(ResponseInterface::class);
        $response->expects($this->once())
            ->method('getBody')
            ->willReturn('{"message":"مواعيد deleted successfully"}');

        $مواعيد = new مواعيد($this->mockPDO);
        $result = $مواعيد->deleteمواعيد($request, $response);

        $this->assertEquals('{"message":"مواعيد deleted successfully"}', $result->getBody());
    }
}