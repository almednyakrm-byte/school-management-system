<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\مدارسController;
use App\Repository\مدارسRepository;
use App\Entity\مدارس;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testمدارس extends TestCase
{
    private $controller;
    private $repository;
    private $request;

    public function setUp(): void
    {
        $this->repository = $this->createMock(مدارسRepository::class);
        $this->controller = new مدارسController($this->repository);
        $this->request = new Request();
    }

    public function testGetAll(): void
    {
        $expectedResponse = ['data' => []];
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getAll($this->request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetById(): void
    {
        $id = 1;
        $expectedResponse = ['data' => new مدارس()];
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->getById($this->request, $id);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testGetByIdNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $this->controller->getById($this->request, $id);
    }

    public function testCreate(): void
    {
        $data = ['name' => 'Test School'];
        $expectedResponse = ['data' => new مدارس()];
        $this->repository->expects($this->once())
            ->method('create')
            ->with($data)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->create($this->request, $data);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdate(): void
    {
        $id = 1;
        $data = ['name' => 'Test School Updated'];
        $expectedResponse = ['data' => new مدارس()];
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn($expectedResponse['data']);

        $response = $this->controller->update($this->request, $id, $data);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals($expectedResponse, json_decode($response->getContent(), true));
    }

    public function testUpdateNotFound(): void
    {
        $id = 1;
        $data = ['name' => 'Test School Updated'];
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('update')
            ->with($id, $data)
            ->willReturn(null);

        $this->controller->update($this->request, $id, $data);
    }

    public function testDelete(): void
    {
        $id = 1;
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id);

        $response = $this->controller->delete($this->request, $id);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteNotFound(): void
    {
        $id = 1;
        $this->expectException(NotFoundHttpException::class);
        $this->repository->expects($this->once())
            ->method('delete')
            ->with($id)
            ->willReturn(null);

        $this->controller->delete($this->request, $id);
    }
}


Note: This code assumes that the `مدارسController` class has methods for each CRUD operation, and that the `مدارسRepository` class has methods for creating, updating, and deleting `مدارس` entities. The `مدارس` entity is also assumed to have a `name` property. The `Request` object is used to simulate HTTP requests.