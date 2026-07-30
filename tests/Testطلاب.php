<?php

namespace App\Tests\Controller;

use PHPUnit\Framework\TestCase;
use App\Controller\طلابController;
use App\Repository\طلابRepository;
use App\Entity\طلاب;
use PHPUnit\Framework\MockObject\MockObject;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Testطلاب extends TestCase
{
    private $controller;
    private $repository;
    private $pdo;

    protected function setUp(): void
    {
        $this->pdo = $this->createMock('PDO');
        $this->repository = $this->createMock(طلابRepository::class);
        $this->controller = new طلابController($this->repository);
    }

    public function testGetStudents()
    {
        $students = [
            new طلاب('1', 'John Doe', 'john@example.com'),
            new طلاب('2', 'Jane Doe', 'jane@example.com'),
        ];

        $this->repository->expects($this->once())
            ->method('findAll')
            ->willReturn($students);

        $response = $this->controller->getStudents();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($students), $response->getContent());
    }

    public function testGetStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $response = $this->controller->getStudent('1');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testGetStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->getStudent('1');
    }

    public function testCreateStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('save')
            ->with($student);

        $request = new Request();
        $request->request->set('name', 'John Doe');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->createStudent($request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_CREATED, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testUpdateStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $this->repository->expects($this->once())
            ->method('save')
            ->with($student);

        $request = new Request();
        $request->request->set('name', 'John Doe Updated');
        $request->request->set('email', 'john@example.com');

        $response = $this->controller->updateStudent('1', $request);

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(json_encode($student), $response->getContent());
    }

    public function testUpdateStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $request = new Request();
        $request->request->set('name', 'John Doe Updated');
        $request->request->set('email', 'john@example.com');

        $this->controller->updateStudent('1', $request);
    }

    public function testDeleteStudent()
    {
        $student = new طلاب('1', 'John Doe', 'john@example.com');

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn($student);

        $this->repository->expects($this->once())
            ->method('remove')
            ->with($student);

        $response = $this->controller->deleteStudent('1');

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }

    public function testDeleteStudentNotFound()
    {
        $this->expectException(NotFoundHttpException::class);

        $this->repository->expects($this->once())
            ->method('find')
            ->with('1')
            ->willReturn(null);

        $this->controller->deleteStudent('1');
    }
}


This test file covers the following scenarios:

1.  **Get Students**: Tests the `getStudents` method to ensure it returns a JSON response with a list of students.
2.  **Get Student**: Tests the `getStudent` method to ensure it returns a JSON response with a single student.
3.  **Get Student Not Found**: Tests the `getStudent` method to ensure it throws a `NotFoundHttpException` when the student is not found.
4.  **Create Student**: Tests the `createStudent` method to ensure it creates a new student and returns a JSON response with the created student.
5.  **Update Student**: Tests the `updateStudent` method to ensure it updates an existing student and returns a JSON response with the updated student.
6.  **Update Student Not Found**: Tests the `updateStudent` method to ensure it throws a `NotFoundHttpException` when the student is not found.
7.  **Delete Student**: Tests the `deleteStudent` method to ensure it deletes a student and returns a JSON response.
8.  **Delete Student Not Found**: Tests the `deleteStudent` method to ensure it throws a `NotFoundHttpException` when the student is not found.

These tests cover the CRUD operations for the `طلاب` module and ensure that the API endpoints behave as expected.