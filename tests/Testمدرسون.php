<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\ProfesseursController;
use App\Repository\ProfesseursRepository;
use App\Entity\Professeurs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\MockObject\MockBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class TestProfesseurs extends TestCase
{
    private $professeursController;
    private $professeursRepository;
    private $entityManager;

    protected function setUp(): void
    {
        $this->professeursRepository = $this->createMock(ProfesseursRepository::class);
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->professeursController = new ProfesseursController($this->professeursRepository, $this->entityManager);
    }

    public function testGetProfesseurs(): void
    {
        $this->professeursRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([
                new Professeurs('1', 'Name 1', 'Email 1'),
                new Professeurs('2', 'Name 2', 'Email 2'),
            ]);

        $response = $this->professeursController->getProfesseurs();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProfesseur(): void
    {
        $professeur = new Professeurs('1', 'Name 1', 'Email 1');
        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);

        $response = $this->professeursController->getProfesseur('1');
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testCreateProfesseur(): void
    {
        $professeur = new Professeurs('1', 'Name 1', 'Email 1');
        $this->professeursRepository->expects($this->once())
            ->method('save')
            ->with($professeur);

        $request = new Request([], [], ['json' => ['id' => '1', 'name' => 'Name 1', 'email' => 'Email 1']]);
        $response = $this->professeursController->createProfesseur($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProfesseur(): void
    {
        $professeur = new Professeurs('1', 'Name 1', 'Email 1');
        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);
        $this->professeursRepository->expects($this->once())
            ->method('save')
            ->with($professeur);

        $request = new Request([], [], ['json' => ['id' => '1', 'name' => 'Name 1', 'email' => 'Email 1']]);
        $response = $this->professeursController->updateProfesseur('1', $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testDeleteProfesseur(): void
    {
        $professeur = new Professeurs('1', 'Name 1', 'Email 1');
        $this->professeursRepository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($professeur);
        $this->professeursRepository->expects($this->once())
            ->method('remove')
            ->with($professeur);

        $response = $this->professeursController->deleteProfesseur('1');
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }
}


This test file covers the following scenarios:

- `testGetProfesseurs`: Tests the GET request to retrieve all professeurs.
- `testGetProfesseur`: Tests the GET request to retrieve a single professeur by ID.
- `testCreateProfesseur`: Tests the POST request to create a new professeur.
- `testUpdateProfesseur`: Tests the PUT request to update an existing professeur.
- `testDeleteProfesseur`: Tests the DELETE request to delete a professeur.

Each test method uses the `createMock` method to create a mock object for the `ProfesseursRepository` and `EntityManagerInterface` classes. The `expects` method is used to specify the expected behavior of the mock objects. The `willReturn` method is used to specify the return value of the mock objects. The `assert` methods are used to verify the expected behavior of the controller.