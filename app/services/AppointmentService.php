<?php
namespace Asus\Medical\services;
use Asus\Medical\interfaces\AppointmentRepositoryInterface;
use Asus\Medical\interfaces\AppointmentServiceInterface;
use Asus\Medical\models\AppointmentModel;
use Asus\Medical\helpers\AppointmentHelper;
use Exception;
// require_once __DIR__ . '/../repositories/AppointmentRepository.php';
// require_once __DIR__ . '/../interfaces/AppointmentRepositoryInterface.php';
// require_once __DIR__ . '/../interfaces/AppointmentServiceInterface.php';

// require_once __DIR__ . '/../models/AppointmentModel.php';

class AppointmentService implements AppointmentServiceInterface
{
    private AppointmentRepositoryInterface $repo;

    public function __construct(AppointmentRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAvailableSlotsForDoctor(int $doctorId, string $selectedDate): array
    {
        // require_once APPROOT .'/helpers/appointment_helper.php';
        $doctor = $this->repo->findDoctorById($doctorId);
        if (!$doctor) {
            throw new Exception("Doctor not found");
        }

        $timeslot = $this->repo->findTimeslotByDoctorId($doctorId);
        if (!$timeslot) {
            throw new Exception("Doctor timeslot not found");
        }

        $slots = AppointmentHelper::getAvailableSlots($timeslot['start_time'], $timeslot['end_time']);
        $appointments = $this->repo->findAppointmentsByDoctorId($doctorId);
        $bookedTimes = AppointmentHelper::getBookedTimes($appointments, $selectedDate);
        return AppointmentHelper::filterFutureAvailableSlots($slots, $bookedTimes, $selectedDate);
    }

    public function getDoctorById(int $doctorId)
    {
        $doctor = $this->repo->findDoctorById($doctorId);
        if (!$doctor) {
            throw new Exception("Doctor not found");
        }
        return $doctor;
    }

    public function bookAppointmentFromRequest(array $postData, ?array $currentUser): bool
    {
        if (!$currentUser) {
            throw new Exception("Please log in to book an appointment.");
        }

        $userId = $currentUser['id'];
        $time = time();

        // -----------------------------
        // Rate limiting: max 3 per day per user
        // -----------------------------
        if (!isset($_SESSION['booking_requests'])) {
            $_SESSION['booking_requests'] = [];
        }

        $_SESSION['booking_requests'] = array_filter(
            $_SESSION['booking_requests'],
            fn($r) => $r['time'] > $time - 86400
        );

        $count = count(array_filter($_SESSION['booking_requests'], fn($r) => $r['user_id'] === $userId));
        if ($count >= 3) {
            throw new Exception("You have reached the daily booking limit (3).");
        }

        $_SESSION['booking_requests'][] = ['user_id' => $userId, 'time' => $time];

        // -----------------------------
        // Prepare data
        // -----------------------------
        $data = [
            'doctor_id' => (int)($postData['doctor_id'] ?? 0),
            'patient_id' => $userId,
            'timeslot_id' => isset($postData['timeslot_id']) ? (int)$postData['timeslot_id'] : null,
            'appointment_date' => isset($postData['appointment_date']) ? date("Y-m-d", strtotime($postData['appointment_date'])) : null,
            'appointment_time' => isset($postData['appointment_time']) ? date("H:i:s", strtotime($postData['appointment_time'])) : null,
            'reason' => htmlspecialchars(trim($postData['reason'] ?? ''), ENT_QUOTES, 'UTF-8'),
        ];

        // -----------------------------
        // Recaptcha validation
        // -----------------------------
        $recaptchaResponse = $postData['g-recaptcha-response'] ?? '';
        if (!$recaptchaResponse) {
            throw new Exception('CAPTCHA verification is required.');
        }

        $secret = RECAPTCHA_V3_SECRET;
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}");
        $captchaSuccess = json_decode($verify,true);
// var_dump($captchaSuccess); exit;

    $minScore = 0.5; // minimum score to accept
    if (!$captchaSuccess['success'] || ($captchaSuccess['score'] ?? 0) < $minScore) {
        throw new Exception('CAPTCHA verification failed or suspicious request. Score: ' . ($captchaSuccess['score'] ?? 'N/A'));
    }


        // -----------------------------
        // Service-level validation & booking
        // -----------------------------
        return $this->bookAppointment($data);
    }


    public function bookAppointment(array $data): bool
    {
        // Validate mandatory fields here or in controller

        // Check for duplicate
        $appointments = $this->repo->findAppointmentsByDoctorId($data['doctor_id']);
        foreach ($appointments as $a) {
            if ($a['appointment_date'] === $data['appointment_date'] && $a['appointment_time'] === $data['appointment_time']) {
                throw new Exception("Timeslot already booked");
            }
        }

        return $this->repo->bookAppointment([
            $data['doctor_id'],
            $data['patient_id'],
            $data['timeslot_id'], 
            $data['appointment_date'],
            $data['appointment_time'],
            $data['reason'],
            2
        ]);

    }

    public function getAppointmentsByPatient(int $patientId): array
    {
        return $this->repo->findAppointmentsByPatientId($patientId);
    }

    public function cancelAppointment(int $appointmentId): bool
    {
        $appointment = $this->repo->findAppointmentById($appointmentId);
        if (!$appointment) {
            throw new Exception("Appointment not found");
        }
        if (in_array($appointment['status_id'], [1, 3])) {
            throw new Exception("Cannot cancel confirmed or rejected appointment");
        }
        return $this->repo->deleteAppointment($appointmentId);
    }

    public function updateAppointmentStatus(int $appointmentId, int $statusId): bool
    {
        $appointment = $this->repo->findAppointmentById($appointmentId);
        if (!$appointment) {
            throw new Exception("Appointment not found");
        }

        $model = new AppointmentModel();
        $model->created_at = $appointment['created_at'];
        $model->reason = $appointment['reason'];
        $model->timeslot_id = $appointment['timeslot_id'];
        $model->appointment_date = $appointment['appointment_date'];
        $model->appointment_time = $appointment['appointment_time'];
        $model->user_id = $appointment['user_id'];
        $model->doctor_id = $appointment['doctor_id'];
        $model->status_id = $statusId;

        return $this->repo->updateAppointment($appointmentId, $model->toArray());
    }

   
}
