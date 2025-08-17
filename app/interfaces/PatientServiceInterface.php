<?php
namespace Asus\Medical\interfaces;
interface PatientServiceInterface
{
    public function getDoctorProfile(int $doctorId, ?string $selectedDate = null): array;
    public function listDoctors(): array;
    public function uploadProfileImage(array $files): array;

    public function removeProfileImage(): array;

    public function getUserProfile(): array;
    public function sendContactMessage(string $fullName, string $emailAddress, string $subject, string $message): array;
}
