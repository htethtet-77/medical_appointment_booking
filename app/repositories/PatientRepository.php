<?php
namespace Asus\Medical\repositories;
use Asus\Medical\interfaces\PatientRepositoryInterface;
use Asus\Medical\libraries\Database;
// require_once __DIR__ . '/../interfaces/PatientRepositoryInterface.php';

class PatientRepository implements PatientRepositoryInterface
{
    protected $db;

    public function __construct(Database $db )
    {
        $this->db = $db;
    }

    public function getDoctorProfile(int $doctorId)
    {
        return $this->db->columnFilter('doctor_view', 'user_id', $doctorId);
    }

    public function getDoctorTimeslot(int $doctorId)
    {
        return $this->db->columnFilter('timeslots', 'user_id', $doctorId);
    }

    public function findAppointmentsByDoctorId(int $doctorId): array
    {
        return $this->db->columnFilterAll('appointment', 'doctor_id', $doctorId) ?? [];
    }


    public function listDoctors()
    {
        return $this->db->readAll('doctor_view');
    }

    public function getUserById(int $userId)
    {
        return $this->db->getById('users', $userId);
    }

    public function updateUserProfileImage(int $userId, string $filename)
    {
        return $this->db->update('users', $userId, ['profile_image' => $filename]);
    }
}
