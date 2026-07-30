<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\MedironController;
use App\Repository\MedironRepository;
use App\Service\MedironService;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TestMediron extends TestCase
{
    private $controller;
    private $repository;
    private $service;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(MedironRepository::class);
        $this->service = $this->createMock(MedironService::class);
        $this->controller = new MedironController($this->repository, $this->service);
    }

    public function testGetMedirons()
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                ['id' => 1, 'name' => 'Mediron 1'],
                ['id' => 2, 'name' => 'Mediron 2'],
            ]);

        $response = $this->controller->getMedirons();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateMediron()
    {
        $data = ['name' => 'Mediron 3'];
        $this->service->expects($this->once())
            ->method('createMediron')
            ->with($data)
            ->willReturn(['id' => 3, 'name' => 'Mediron 3']);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->createMediron($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateMediron()
    {
        $data = ['name' => 'Mediron 1 Updated'];
        $this->service->expects($this->once())
            ->method('updateMediron')
            ->with(1, $data)
            ->willReturn(['id' => 1, 'name' => 'Mediron 1 Updated']);

        $request = new Request([], [], [], [], [], json_encode($data));
        $response = $this->controller->updateMediron(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteMediron()
    {
        $this->service->expects($this->once())
            ->method('deleteMediron')
            ->with(1)
            ->willReturn(true);

        $response = $this->controller->deleteMediron(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}