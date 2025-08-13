<?php
// namespace App\Interfaces;
interface AppointmentServiceInterface
{
    public function getAvailableSlotsForDoctor(int $doctorId, string $selectedDate): array;
    public function getDoctorById(int $doctorId);
    public function bookAppointment(array $data): bool;
    public function getAppointmentsByPatient(int $patientId): array;
    public function cancelAppointment(int $appointmentId): bool;
    public function updateAppointmentStatus(int $appointmentId, int $statusId): bool;
}
