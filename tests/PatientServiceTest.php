<?php
require_once __DIR__ . '/../app/repositories/PatientRepository.php';
require_once __DIR__ . '/../app/services/PatientService.php';

use PHPUnit\Framework\TestCase;

class PatientServiceTest extends TestCase
{
    private $patientRepositoryMock;
    private $imageUploadServiceMock;
    private $appointmentServiceMock;
    private $patientService;

    protected function setUp(): void
    {
        $this->patientRepositoryMock = $this->createMock(PatientRepository::class);
        $this->imageUploadServiceMock = $this->createMock(ImageUploadService::class);
        $this->appointmentServiceMock = $this->createMock(AppointmentService::class);

        $this->patientService = new PatientService(
            $this->patientRepositoryMock,
            $this->imageUploadServiceMock,
            $this->appointmentServiceMock
        );
    }

    public function testListDoctorsReturnsDoctors()
    {
        $expectedDoctors = [
            ['id' => 1, 'name' => 'Dr. Smith'],
            ['id' => 2, 'name' => 'Dr. Jane'],
        ];

        // Mock the repository method
        $this->patientRepositoryMock->method('listDoctors')
            ->willReturn($expectedDoctors);

        $result = $this->patientService->listDoctors();

        $this->assertArrayHasKey('doctors', $result);
        $this->assertEquals($expectedDoctors, $result['doctors']);
    }
}
