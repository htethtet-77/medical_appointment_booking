<?php
require_once __DIR__ . '/../services/AppointmentService.php';
// require_once __DIR__ . '/../repositories/AppointmentRepository.php';

class Appointment extends Controller
{
    private $service;

    public function __construct()
    {
        $db=new Database();
        $repo = new AppointmentRepository($db);
        $this->service = new AppointmentService($repo);
    }

    public function appointmentform($doctorId)
    {
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

        public function book()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('pages/home');
        }

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
    }

    public function appointmentlist()
    {
        $user = $_SESSION['current_user'] ?? null;
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

    public function confirm($appointmentId)
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
    }
}
