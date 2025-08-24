<?php
namespace Asus\Medical\controllers;
use Asus\Medical\libraries\Controller;
use Asus\Medical\interfaces\AppointmentServiceInterface;
use function Asus\Medical\helpers\setMessage;
use Exception;
use Asus\Medical\Middleware\AuthMiddleware;
use Asus\Medical\Middleware\CsrfMiddleware;
// require_once __DIR__ . '/../services/AppointmentService.php';
// require_once __DIR__ . '/../interfaces/AppointmentServiceInterface.php';

class Appointment extends Controller
{
     protected AppointmentServiceInterface $service;

    public function __construct(AppointmentServiceInterface $service)
    {
       
        $this->service = $service;
    }

    public function appointmentform($encodedId=null)
    {
        $decoded = base64_decode($encodedId,true);
        $doctorId=(int)$decoded;
         AuthMiddleware::allowRoles([ROLE_PATIENT]);
        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            setMessage('error', "You need to register first");
            return redirect("pages/register");
        }

        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        try {
            $availableSlots = $this->service->getAvailableSlotsForDoctor($doctorId, $selectedDate);
            $doctor = $this->service->getDoctorById($doctorId); // or inject doctor service separately
        } catch (Exception $e) {
            die($e->getMessage());
        }

        $this->view('pages/appointmentform', [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots,
            'selected_date' => $selectedDate
        ]);
    }
    public function getslots()
    {
        $doctorId = $_GET['doctor_id'] ?? null;
        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        if (!$doctorId) {
            echo json_encode([]);
            return;
        }

        try {
            $slots = $this->service->getAvailableSlotsForDoctor((int)$doctorId, $selectedDate);
            echo json_encode($slots);
        } catch (Exception $e) {
            echo json_encode([]);
        }
        exit;
    }

    public function book()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        return redirect('pages/home');
    }

    CsrfMiddleware::validateToken();

    try {
        $this->service->bookAppointmentFromRequest($_POST, $_SESSION['current_patient'] ?? null);
        setMessage('success', 'Appointment booked successfully.');
        return redirect('appointment/appointmentlist');

    } catch (Exception $e) {

        $errorMessage = $e->getMessage();
        setMessage('error', $errorMessage);

        // If error is booking limit reached, go back to appointment list
        if ($errorMessage === "You have reached the daily booking limit (3).") {
            return redirect('appointment/appointmentlist');
        }

        // Otherwise, return to appointment form with doctorId properly base64 encoded
        $doctorId = $_POST['doctor_id'] ?? '';
        $encodedDoctorId = base64_encode($doctorId);
        return redirect("appointment/appointmentform/{$encodedDoctorId}");
    }
}

      /*  public function book()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('pages/home');
        }
        // At the start of book() method, after checking POST
        $ip = $_SERVER['REMOTE_ADDR'];
        $time = time();
        $limit = 5; // max 5 bookings per 10 seconds
        $window = 10;

        if (!isset($_SESSION['booking_requests'])) {
            $_SESSION['booking_requests'] = [];
        }

        // Remove old requests
        $_SESSION['booking_requests'] = array_filter($_SESSION['booking_requests'], fn($r) => $r['time'] > $time - $window);

        // Count requests from this IP
        $count = count(array_filter($_SESSION['booking_requests'], fn($r) => $r['ip'] === $ip));
        if ($count >= $limit) {
            setMessage('error', 'Too many booking attempts. Please wait a moment.');
            return redirect('pages/home');
        }

        // Record current request
        $_SESSION['booking_requests'][] = ['ip' => $ip, 'time' => $time];

        CsrfMiddleware::validateToken();


        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            setMessage('error', 'Please log in to book an appointment.');
            return redirect('pages/login');
        }

        $data = [
            'doctor_id' => $_POST['doctor_id'] ?? null,
            'patient_id' => $user['id'],
            'timeslot_id' => isset($_POST['timeslot_id']) ? (int)$_POST['timeslot_id'] : null,  // IMPORTANT: cast to int
            'appointment_date' => isset($_POST['appointment_date']) ? date("Y-m-d", strtotime($_POST['appointment_date'])) : null,
            'appointment_time' => isset($_POST['appointment_time']) ? date("H:i:s", strtotime($_POST['appointment_time'])) : null,
            'reason' => trim($_POST['reason'] ?? ''),
        ];

        // Basic validation example:
        if (!$data['doctor_id'] || !$data['timeslot_id'] || !$data['appointment_date'] || !$data['appointment_time']) {
            setMessage('error', 'Please fill all required fields.');
            return redirect("appointment/appointmentform/{$data['doctor_id']}");
        }

        try {
            $success = $this->service->bookAppointment($data);
            if (!$success) {
                throw new Exception("Failed to create appointment.");
            }
            setMessage('success', 'Appointment booked successfully.');
            return redirect('appointment/appointmentlist');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            return redirect("appointment/appointmentform/{$data['doctor_id']}");
        }
    }*/

    public function appointmentlist()
    {
        $user = $_SESSION['current_patient'] ?? null;
        if (!$user) {
            return redirect('pages/appointment');
        }

        $appointments = $this->service->getAppointmentsByPatient($user['id']);

        $this->view("pages/appointment", [
            'appointments' => $appointments,
            'user' => $user,
        ]);
    }

    public function delete($id)
    {
        try {
            $this->service->cancelAppointment($id);
            setMessage('success', 'Appointment cancelled successfully.');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect('appointment/appointmentlist');
    }

   /* public function confirm($appointmentId)
    {
        try {
            $this->service->updateAppointmentStatus($appointmentId, 1);
            setMessage('success', 'Appointment confirmed successfully.');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect("doctor/dash");
    }

    public function reject($appointmentId)
    {
        try {
            $this->service->updateAppointmentStatus($appointmentId, 3);
            setMessage('success', 'Appointment rejected successfully.');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }
        redirect("doctor/dash");
    }*/
    public function confirm($appointmentId)
{
    try {
        $this->service->updateAppointmentStatus($appointmentId, 1);
        $message = 'Appointment confirmed successfully.';

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode([
                'success' => true,
                'message' => $message,
                'status' => 'Confirmed'
            ]);
            exit;
        }

        setMessage('success', $message);
    } catch (Exception $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
        setMessage('error', $e->getMessage());
    }
    redirect("doctor/dash");
}

public function reject($appointmentId)
{
    try {
        $this->service->updateAppointmentStatus($appointmentId, 3);
        $message = 'Appointment rejected successfully.';

        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode([
                'success' => true,
                'message' => $message,
                'status' => 'Cancelled'
            ]);
            exit;
        }

        setMessage('success', $message);
    } catch (Exception $e) {
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
            exit;
        }
        setMessage('error', $e->getMessage());
    }
    redirect("doctor/dash");
}
// Returns a new CSRF token as JSON for refreshing the token via AJAX every 5 minutes
public function refreshCsrfToken()
{
    header('Content-Type: application/json');
    $token =CsrfMiddleware::generateToken();
    echo json_encode(['csrf_token' => $token]);
    exit;
}


}
