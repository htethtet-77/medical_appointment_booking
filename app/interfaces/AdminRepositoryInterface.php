<?php
interface AdminRepositoryInterface
{
    public function getAllDoctors(): array;

    public function emailExists(string $email): bool;

    public function phoneExists(string $phone): bool;

    public function addDoctor(array $params): int;

    public function findTimeslotsByUserId(int $userId): array;

    public function findAppointmentsByTimeslotId(int $timeslotId): array;

    public function deleteAppointment(int $appointmentId): bool;

    public function deleteTimeslot(int $timeslotId): bool;

    public function findDoctorProfileByUserId(int $userId): ?array;

    public function deleteDoctorProfile(int $profileId): bool;

    public function deleteUser(int $userId): bool;

    public function findUserById(int $userId): ?array;

    public function updateDoctor(array $params): bool;

    public function getPatients(): array;

    public function getAppointments(): array;
}
