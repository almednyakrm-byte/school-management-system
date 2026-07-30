<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Controller\ProfesseursController;
use App\Repository\ProfesseursRepository;
use App\Entity\Professeurs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\QueryException;

class TestProfesseurs extends TestCase
{
    private $controller;
    private $repository;
    private $entityManager;
    private $request;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(ProfesseursRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->request = $this->createMock(Request::class);
        $this->controller = new ProfesseursController($this->repository, $this->entityManager);
    }

    public function testGetProfesseurs(): void
    {
        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn([new Professeurs()]);

        $response = $this->controller->getProfesseurs($this->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testGetProfesseur(): void
    {
        $id = 1;
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn($id);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Professeurs());

        $response = $this->controller->getProfesseur($this->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testCreateProfesseur(): void
    {
        $professeur = new Professeurs();
        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($professeur);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John', 'email' => 'john@example.com']);

        $response = $this->controller->createProfesseur($this->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
    }

    public function testUpdateProfesseur(): void
    {
        $id = 1;
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn($id);

        $professeur = new Professeurs();
        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn($professeur);

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($professeur);
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $this->request->expects($this->once())
            ->method('request')
            ->willReturn(['name' => 'John', 'email' => 'john@example.com']);

        $response = $this->controller->updateProfesseur($this->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteProfesseur(): void
    {
        $id = 1;
        $this->request->expects($this->once())
            ->method('get')
            ->with('id')
            ->willReturn($id);

        $this->repository->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(new Professeurs());

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with(new Professeurs());
        $this->entityManager->expects($this->once())
            ->method('flush')
            ->willReturn(null);

        $response = $this->controller->deleteProfesseur($this->request);
        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

1.  **Get Professeurs**: Tests the `getProfesseurs` method to ensure it returns a JSON response with a 200 status code when retrieving all professeurs.
2.  **Get Professeur**: Tests the `getProfesseur` method to ensure it returns a JSON response with a 200 status code when retrieving a specific professeur by ID.
3.  **Create Professeur**: Tests the `createProfesseur` method to ensure it creates a new professeur and returns a JSON response with a 201 status code.
4.  **Update Professeur**: Tests the `updateProfesseur` method to ensure it updates an existing professeur and returns a JSON response with a 200 status code.
5.  **Delete Professeur**: Tests the `deleteProfesseur` method to ensure it deletes a professeur and returns a JSON response with a 204 status code.

Each test method uses the `createMock` method to create mock objects for the `ProfesseursRepository` and `EntityManagerInterface` classes. These mock objects are then used to simulate the behavior of the repository and entity manager during the test.

The `expects` method is used to specify the expected behavior of the mock objects, and the `willReturn` method is used to specify the return value of the mock objects.

The `assertInstanceOf` method is used to ensure that the response object is an instance of `JsonResponse`, and the `assertEquals` method is used to ensure that the status code of the response is as expected.

Note that this test file assumes that the `ProfesseursController` class has already been implemented and is being tested. The test file only focuses on verifying the CRUD API operations on the `Professeurs` module.