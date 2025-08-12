<?php
require_once __DIR__ . '/../interfaces/DoctorRepositoryInterface.php';

class DoctorRepository implements DoctorRepositoryInterface{
    private $db;
    public function __construct(Database $db){
        $this->db=$db;
    }
    public function getAppointmentByDoctorId(int $doctorUserId){
        return $this->db->columnFilterAll('appointment_view','doctor_user_id',$doctorUserId);
    }
    public function getDoctorById(int $doctorUserId) 
    {
        return $this->db->getById('users', $doctorUserId);
    }

public function updateDoctorPasswordOnly(int $doctorId, string $encodedPassword): bool
    {  
        return $this->db->update('users', $doctorId,  ['password' => $encodedPassword]);
    }


}
?>