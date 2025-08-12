<?php
interface DoctorRepositoryInterface{
    public function getAppointmentByDoctorId(int $doctorUserId);
    public function getDoctorById(int $id) ;
public function updateDoctorPasswordOnly(int $doctorUserId, string $encodedPassword): bool;



}
?>