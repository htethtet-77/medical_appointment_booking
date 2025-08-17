<?php
namespace Asus\Medical\repositories;
use Asus\Medical\interfaces\AppointmentRepositoryInterface;
use Asus\Medical\libraries\Database;
// require_once __DIR__ . '/../interfaces/AppointmentRepositoryInterface.php';
class AppointmentRepository implements AppointmentRepositoryInterface
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function findDoctorById(int $doctorId)
    {
        return $this->db->columnFilter('doctor_view', 'user_id', $doctorId);
    }

    public function findTimeslotByDoctorId(int $doctorId)
    {
        return $this->db->columnFilter('timeslots', 'user_id', $doctorId);
    }

    public function findAppointmentsByDoctorId(int $doctorId): array
    {
        return $this->db->columnFilterAll('appointment', 'doctor_id', $doctorId) ?? [];
    }

    public function findAppointmentsByPatientId(int $patientId): array
    {
        return $this->db->columnFilterAll('appointment_view', 'patient_id', $patientId) ?? [];
    }

    public function findAppointmentById(int $id)
    {
        return $this->db->columnFilter('appointment', 'id', $id);
    }

    public function bookAppointment(array $data): bool
    {
        return $this->db->callProcedure('book_appointment', $data);
    }

    public function updateAppointment(int $id, array $data): bool
    {
        return $this->db->update('appointment', $id, $data);
    }

    public function deleteAppointment(int $id): bool
    {
        return $this->db->callProcedure('delete_appointment', [$id]);
    }
}
