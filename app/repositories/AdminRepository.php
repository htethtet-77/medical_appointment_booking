<?php
namespace Asus\Medical\repositories;
// require_once __DIR__ . '/../interfaces/AdminRepositoryInterface.php';
use Asus\Medical\interfaces\AdminRepositoryInterface;
use Asus\Medical\libraries\Database;
class AdminRepository implements AdminRepositoryInterface
{
    protected  $db;

    public function __construct(Database $db )
    {
        // Allow dependency injection, or default to a new Database
        $this->db = $db ?? new Database();
    }

    public function emailExists(string $email): bool
    {
        $result = $this->db->columnFilter('users', 'email', $email);
        return !empty($result);
    }

    public function phoneExists(string $phone): bool
    {
        $result = $this->db->columnFilter('users', 'phone', $phone);
        return !empty($result);
    }

    public function getAllDoctors(): array
    {
        return $this->db->readAll('doctor_view') ?: [];
    }

    public function findUserById(int $user_id): ?array
    {
        $user = $this->db->getById('users', $user_id);
        return $user ?: null;
    }

    public function findDoctorProfileByUserId(int $user_id): ?array
    {
        $profile = $this->db->columnFilter('doctorprofile', 'user_id', $user_id);
        return !empty($profile) ? $profile : null;
    }

    public function findTimeslotsByUserId(int $user_id): array
    {
        return $this->db->columnFilter('timeslots', 'user_id', $user_id) ?: [];
    }

    public function deleteTimeslot(int $id): bool
    {
        return (bool) $this->db->delete('timeslots', $id);
    }

    public function findAppointmentsByTimeslotId(int $timeslot_id): array
    {
        return $this->db->columnFilterAll('appointment', 'timeslot_id', $timeslot_id) ?: [];
    }

    public function deleteAppointment(int $id): bool
    {
        return (bool) $this->db->delete('appointment', $id);
    }

    public function deleteDoctorProfile(int $id): bool
    {
        return (bool) $this->db->delete('doctorprofile', $id);
    }

    public function deleteUser(int $user_id): bool
    {
        return (bool) $this->db->delete('users', $user_id);
    }

    public function addDoctor(array $params): int
    {
        $result = $this->db->adddoctor('add_doctor', $params);
        return (is_numeric($result) && (int)$result > 0) ? (int)$result : 0;
    }

    public function updateDoctor(array $params): bool
    {
        return (bool) $this->db->callProcedure('update_doctor', $params);
    }

    public function getPatients(): array
    {
        return $this->db->columnFilterAll('users', 'type_id', 3) ?: [];
    }

    public function getAppointments(): array
    {
        return $this->db->readAll('appointment_view') ?: [];
    }
}
