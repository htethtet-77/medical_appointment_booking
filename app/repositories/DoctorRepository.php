<?php
class DoctorRepository{
    private $db;
    public function __construct(Database $db){
        $this->db=$db;
    }
    public function getAppointmentByDoctorId(int $doctorUserId){
        return $this->db->columnFilterAll('appointment_view','doctor_user_id',$doctorUserId);
    }
}
?>