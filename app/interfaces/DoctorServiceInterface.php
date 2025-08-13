<?php
interface DoctorServiceInterface
{
    public function getDoctorDashboardData(int $doctorUserId): array;
    public function getDoctorById(int $doctorId);
    public function changePassword(int $doctorId, string $currentPassword, string $newPassword, string $confirmPassword): bool;
    public function updatePassword(int $doctorId, string $newPassword): bool;
}
