<?php

namespace App\Tests\Controller;

use App\Controller\DegreesController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use PDO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class Testدرجات extends TestCase
{
    private $controller;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock(PDO::class);
        $this->controller = new DegreesController($this->pdo);
    }

    public function testGetDegrees()
    {
        $this->pdo->expects($this->once())
            ->method('query')
            ->with('SELECT * FROM degrees')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request();
        $response = $this->controller->getDegrees($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testPostDegree()
    {
        $degree = ['name' => 'degree1', 'description' => 'description1'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('INSERT INTO degrees (name, description) VALUES (:name, :description)')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['degree' => $degree]);
        $response = $this->controller->postDegree($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testPutDegree()
    {
        $degree = ['name' => 'degree1', 'description' => 'description1'];
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('UPDATE degrees SET name = :name, description = :description WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['degree' => $degree]);
        $response = $this->controller->putDegree($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteDegree()
    {
        $this->pdo->expects($this->once())
            ->method('prepare')
            ->with('DELETE FROM degrees WHERE id = :id')
            ->willReturn($this->createMock(\PDOStatement::class));

        $request = new Request([], [], ['id' => 1]);
        $response = $this->controller->deleteDegree($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}