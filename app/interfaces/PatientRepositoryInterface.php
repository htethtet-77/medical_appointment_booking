<?php
namespace Asus\Medical\interfaces;
interface PatientRepositoryInterface
{
    public function getDoctorProfile(int $doctorId);
    public function getDoctorTimeslot(int $doctorId);
    public function findAppointmentsByDoctorId(int $doctorId);
    public function listDoctors();
    public function getUserById(int $userId);
    public function updateUserProfileImage(int $userId, string $filename);
}
