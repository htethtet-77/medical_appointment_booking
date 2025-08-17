<?php
namespace Asus\Medical\interfaces;
interface AppointmentRepositoryInterface
{
    public function findDoctorById(int $doctorId);
    public function findTimeslotByDoctorId(int $doctorId);
    public function findAppointmentsByDoctorId(int $doctorId): array;
    public function findAppointmentsByPatientId(int $patientId): array;
    public function findAppointmentById(int $id);
    public function bookAppointment(array $data): bool;
    public function updateAppointment(int $id, array $data): bool;
    public function deleteAppointment(int $id): bool;
    
}
