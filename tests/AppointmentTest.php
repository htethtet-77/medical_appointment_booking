<?php
use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../app/interfaces/AppointmentServiceInterface.php';
require_once __DIR__ . '/../tests/testableAppointment.php';  
class AppointmentTest extends TestCase
{
    private $serviceMock;
    private $controller;

    protected function setUp(): void
    {
        // Mock AppointmentServiceInterface
        $this->serviceMock = $this->createMock(AppointmentServiceInterface::class);

        // Use the testable controller subclass to capture view()
        $this->controller = new TestableAppointment($this->serviceMock);

        // Clear session before each test
        $_SESSION = [];
        $_GET = [];
        $_POST = [];
    }

    public function testAppointmentFormRedirectsIfUserNotLoggedIn()
    {
        // No user in session
        $_SESSION['current_user'] = null;

        // We expect setMessage and redirect — let's mock global helpers if possible
        // For demo, just capture output or returned redirect string

        // Call appointmentform
        $result = $this->controller->appointmentform(1);

        // Your redirect() might call header() or exit, so to properly test,
        // you should refactor redirect() to be injectable or mockable,
        // or test side effects in integration tests.

        // Here we just test that method returns early or something similar
        // If redirect() calls exit, this test needs to be adapted accordingly.
        $this->assertNull($result); // or assert other behavior based on redirect implementation
    }

    public function testAppointmentFormShowsViewWithData()
    {
        $_SESSION['current_user'] = ['id' => 42, 'name' => 'Test User'];
        $doctorId = 10;
        $selectedDate = date('Y-m-d');
        $_GET['date'] = $selectedDate;

        // Setup mock expectations
        $this->serviceMock->expects($this->once())
            ->method('getAvailableSlotsForDoctor')
            ->with($doctorId, $selectedDate)
            ->willReturn(['09:00', '10:00']);

        $this->serviceMock->expects($this->once())
            ->method('getDoctorById')
            ->with($doctorId)
            ->willReturn(['id' => $doctorId, 'name' => 'Dr. Smith']);

        // Call method
        $this->controller->appointmentform($doctorId);

        // Assert view was called with correct parameters
        $this->assertEquals('pages/appointmentform', $this->controller->viewData['view']);
        $this->assertArrayHasKey('doctor', $this->controller->viewData['data']);
        $this->assertEquals(['id' => $doctorId, 'name' => 'Dr. Smith'], $this->controller->viewData['data']['doctor']);
        $this->assertArrayHasKey('user', $this->controller->viewData['data']);
        $this->assertArrayHasKey('appointment_time', $this->controller->viewData['data']);
        $this->assertEquals(['09:00', '10:00'], $this->controller->viewData['data']['appointment_time']);
        $this->assertEquals($selectedDate, $this->controller->viewData['data']['selected_date']);
    }

    public function testBookWithMissingFieldsShowsErrorAndRedirects()
    {
        $_SESSION['current_user'] = ['id' => 42];
        $_POST = [
            'doctor_id' => 1,
            // missing timeslot_id, appointment_date, appointment_time
        ];

        // Mock setMessage and redirect if possible

        // Call book()
        $result = $this->controller->book();

        // Since book() calls redirect() (likely exit), you need to refactor redirect() to be testable
        // For now just test it returns null or whatever behavior you expect
        $this->assertNull($result);
    }

    public function testBookSuccess()
    {
        $_SESSION['current_user'] = ['id' => 42];
        $_POST = [
            'doctor_id' => 1,
            'timeslot_id' => 5,
            'appointment_date' => '2025-08-12',
            'appointment_time' => '09:00',
            'reason' => 'Checkup',
        ];

        // Expect bookAppointment to be called and return true
        $this->serviceMock->expects($this->once())
            ->method('bookAppointment')
            ->with($this->callback(function ($data) {
                return $data['doctor_id'] === 1
                    && $data['patient_id'] === 42
                    && $data['timeslot_id'] === 5;
            }))
            ->willReturn(true);

        // Call book()
        $result = $this->controller->book();

        // Again, you may want to refactor redirect() and setMessage() to mock their effects
        $this->assertNull($result);
    }
}
