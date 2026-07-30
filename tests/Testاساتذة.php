<?php

namespace App\Tests\Controller;

use App\Controller\ProfesseurController;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\RouterInterface;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;

class TestProfesseurController extends TestCase
{
    private $controller;
    private $router;
    private $pdo;

    public function setUp(): void
    {
        $this->router = $this->createMock(RouterInterface::class);
        $this->pdo = $this->createMock(Connection::class);
        $this->controller = new ProfesseurController($this->router, $this->pdo);
    }

    public function testGetProfesseurs(): void
    {
        $this->pdo->expects($this->once())
            ->method('fetchAll')
            ->willReturn([
                ['id' => 1, 'nom' => 'Professeur 1'],
                ['id' => 2, 'nom' => 'Professeur 2'],
            ]);

        $response = $this->controller->getProfesseurs();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProfesseurById(): void
    {
        $this->pdo->expects($this->once())
            ->method('fetch')
            ->willReturn(['id' => 1, 'nom' => 'Professeur 1']);

        $response = $this->controller->getProfesseur(1);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testGetProfesseurByIdNotFound(): void
    {
        $this->pdo->expects($this->once())
            ->method('fetch')
            ->willReturn(null);

        $this->expectException(NotFoundHttpException::class);
        $this->controller->getProfesseur(1);
    }

    public function testCreateProfesseur(): void
    {
        $request = new Request([], [], ['nom' => 'Professeur 3']);
        $this->pdo->expects($this->once())
            ->method('insert')
            ->with('professeurs', ['nom' => 'Professeur 3']);

        $response = $this->controller->createProfesseur($request);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProfesseur(): void
    {
        $request = new Request([], [], ['nom' => 'Professeur 1']);
        $this->pdo->expects($this->once())
            ->method('update')
            ->with('professeurs', ['nom' => 'Professeur 1'], ['id' => 1]);

        $response = $this->controller->updateProfesseur(1, $request);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals('application/json', $response->headers->get('Content-Type'));
    }

    public function testUpdateProfesseurNotFound(): void
    {
        $request = new Request([], [], ['nom' => 'Professeur 1']);
        $this->pdo->expects($this->once())
            ->method('update')
            ->with('professeurs', ['nom' => 'Professeur 1'], ['id' => 1])
            ->willThrowException(new NotFoundHttpException());

        $this->expectException(NotFoundHttpException::class);
        $this->controller->updateProfesseur(1, $request);
    }

    public function testDeleteProfesseur(): void
    {
        $this->pdo->expects($this->once())
            ->method('delete')
            ->with('professeurs', ['id' => 1]);

        $response = $this->controller->deleteProfesseur(1);
        $this->assertEquals(Response::HTTP_NO_CONTENT, $response->getStatusCode());
    }

    public function testDeleteProfesseurNotFound(): void
    {
        $this->pdo->expects($this->once())
            ->method('delete')
            ->with('professeurs', ['id' => 1])
            ->willThrowException(new NotFoundHttpException());

        $this->expectException(NotFoundHttpException::class);
        $this->controller->deleteProfesseur(1);
    }
}


This test file covers the following scenarios:

*   `testGetProfesseurs`: Verifies that the `getProfesseurs` method returns a successful response with a list of professeurs.
*   `testGetProfesseurById`: Verifies that the `getProfesseur` method returns a successful response with a single professeur's details.
*   `testGetProfesseurByIdNotFound`: Verifies that the `getProfesseur` method throws a `NotFoundHttpException` when the professeur is not found.
*   `testCreateProfesseur`: Verifies that the `createProfesseur` method creates a new professeur and returns a successful response.
*   `testUpdateProfesseur`: Verifies that the `updateProfesseur` method updates an existing professeur and returns a successful response.
*   `testUpdateProfesseurNotFound`: Verifies that the `updateProfesseur` method throws a `NotFoundHttpException` when the professeur is not found.
*   `testDeleteProfesseur`: Verifies that the `deleteProfesseur` method deletes a professeur and returns a successful response.
*   `testDeleteProfesseurNotFound`: Verifies that the `deleteProfesseur` method throws a `NotFoundHttpException` when the professeur is not found.