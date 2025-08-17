<?php
namespace Asus\Medical\services;
use Asus\Medical\interfaces\DoctorServiceInterface;
use Asus\Medical\interfaces\DoctorRepositoryInterface;
use Exception;
use DateTime;
// require_once __DIR__ . '/../interfaces/DoctorRepositoryInterface.php';
// require_once __DIR__ . '/../interfaces/DoctorServiceInterface.php';

// require_once __DIR__ . '/../repositories/DoctorRepository.php';



class DoctorService implements DoctorServiceInterface
{
    private DoctorRepositoryInterface $doctorRepository;
 

    public function __construct(DoctorRepositoryInterface $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    
    }

    public function getDoctorDashboardData(int $doctorUserId): array
    {
        $allAppointments = $this->doctorRepository->getAppointmentByDoctorId($doctorUserId);

        if (!is_array($allAppointments)) {
        $allAppointments = [];
    }

        $appointmentsByDate = [];
        $totalAppointments = 0;
        $todaysAppointments = 0;
        $dateString = (new DateTime())->format('Y-m-d');

        foreach ($allAppointments as $appointment) {
            $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date']));

            if (!isset($appointmentsByDate[$appointmentDate])) {
                $appointmentsByDate[$appointmentDate] = [];
            }
            $appointmentsByDate[$appointmentDate][] = $appointment;
            $totalAppointments++;

            if ($appointmentDate === $dateString) {
                $todaysAppointments++;
            }
        }

        $totalPatients = count(array_unique(array_column($allAppointments, 'patient_id')));

        krsort($appointmentsByDate);

        return [
            'appointmentsByDate' => $appointmentsByDate,
            'todayDate' => $dateString,
            'totalAppointments' => $totalAppointments,
            'todaysAppointments' => $todaysAppointments,
            'totalPatients' => $totalPatients,
        ];
    }
    public function getDoctorById(int $doctorId)
    {
       return $this->doctorRepository->getDoctorById($doctorId);
      
    }

  public function changePassword(int $doctorId, string $currentPassword, string $newPassword, string $confirmPassword): bool
    {
        if (empty($currentPassword)) {
            throw new Exception("Please enter your current password.");
        }

        if (empty($newPassword)) {
            throw new Exception("Please enter a new password.");
        }

        if ($newPassword !== $confirmPassword) {
            throw new Exception("New password and confirm password do not match.");
        }

        $doctor = $this->getDoctorById($doctorId);
        if (!$doctor) {
            throw new Exception("Doctor not found.");
        }

        if (base64_encode($currentPassword)!== $doctor['password']) {
            throw new Exception("Current password is incorrect.");
        }

        return $this->updatePassword($doctorId, $newPassword);
    }

    public function updatePassword(int $doctorId, string $newPassword): bool
    {
        $encodedPassword = base64_encode($newPassword);
       
        return $this->doctorRepository->updateDoctorPasswordOnly($doctorId, $encodedPassword);
    }
}
?>
