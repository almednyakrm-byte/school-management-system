<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MaterialsController;
use App\Repository\MaterialsRepository;
use App\Entity\Materials;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testمواد extends TestCase
{
    private $controller;
    private $repository;
    private $materials;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(MaterialsRepository::class);
        $this->materials = new Materials();
        $this->controller = new MaterialsController($this->repository);
    }

    public function testGetMaterials()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([$this->materials]);

        $response = $this->controller->getMaterials();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetMaterialById()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($this->materials);

        $response = $this->controller->getMaterial(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetMaterialByIdNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getMaterial(1);
    }

    public function testCreateMaterial()
    {
        $request = new Request([], [], [], [], [], ['_method' => 'POST'], json_encode(['name' => 'Material 1']));
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->materials);

        $response = $this->controller->createMaterial($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateMaterial()
    {
        $request = new Request([], [], [], [], [], ['_method' => 'PUT'], json_encode(['name' => 'Material 1']));
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($this->materials);
        $this->repository->expects($this->once())
            ->method('save')
            ->with($this->materials);

        $response = $this->controller->updateMaterial(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateMaterialNotFound()
    {
        $request = new Request([], [], [], [], [], ['_method' => 'PUT'], json_encode(['name' => 'Material 1']));
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->updateMaterial(1, $request);
    }

    public function testDeleteMaterial()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn($this->materials);
        $this->repository->expects($this->once())
            ->method('remove')
            ->with($this->materials);

        $response = $this->controller->deleteMaterial(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteMaterialNotFound()
    {
        $this->repository->expects($this->once())
            ->method('find')
            ->with(1)
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->deleteMaterial(1);
    }
}