<?php
namespace Asus\Medical\interfaces;
interface AdminServiceInterface
{
    public function getAllDoctors();
    public function addDoctor(array $data, array $file);
    public function deleteDoctor($user_id);
    public function getDoctorDetails($user_id);
    public function updateDoctor(array $data, array $file);
    public function getPatients();
    public function getAppointments();
    public function getDashboardData();
}
