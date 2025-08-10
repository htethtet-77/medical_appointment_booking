<?php
require_once __DIR__ . '/../repositories/PatientRepository.php';
require_once __DIR__ . '/../services/ImageUploadService.php';
require_once __DIR__ . '/../services/AppointmentService.php';

class PatientService
{
    private $patientRepository;
    protected ImageUploadService $imageUploader;
    protected AppointmentService $appointmentService;


    public function __construct(
        PatientRepository $patientRepository,
        ImageUploadService $imageUploader,
        AppointmentService $appointmentService
)
    {
        $this->patientRepository = $patientRepository;
        $this->imageUploader = $imageUploader;
        $this->appointmentService = $appointmentService;

    }

    public function getDoctorProfile($doctorId, $selectedDate = null)
    {
        require_once APPROOT . '/helpers/appointment_helper.php';

        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            return ['redirect' => 'pages/register', 'message' => 'You need to register first'];
        }

        $doctor = $this->patientRepository->getDoctorProfile($doctorId);
        if (!$doctor) {
            die('Doctor not found');
        }

        $time = $this->patientRepository->getDoctorTimeslot($doctorId);
        if (!$time) {
            die('Doctor timeslot not found');
        }

        $selectedDate = $selectedDate ?? date('Y-m-d');
        $availableSlots = $this->appointmentService->getAvailableSlotsForDoctor($doctorId, $selectedDate);

        return [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots,
            'selected_date' => $selectedDate
        ];
    }

    public function listDoctors()
    {
        $doctors = $this->patientRepository->listDoctors();
        return ['doctors' => $doctors];
    }

    public function uploadProfileImage($files)
    {
        try {
            if (!isset($files['profile_image'])) {
                return ['success' => false, 'message' => 'No file uploaded'];
            }

            $file = $files['profile_image'];
            $userId = $_SESSION['current_user']['id'];
            $uploadDir = dirname(APPROOT) . '/public/uploads/';

            // Remove old image if it's not the default
            $currentUser = $this->patientRepository->getUserById($userId);
            if ($currentUser && $currentUser['profile_image'] !== 'default_profile.jpg') {
                $oldImagePath = $uploadDir . $currentUser['profile_image'];
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }

            // Use ImageUploadService
            $filename = $this->imageUploader->upload(
                $file,
                $uploadDir,
                'profile_' . $userId . '_'
            );

            // Update DB & session
            $this->patientRepository->updateUserProfileImage($userId, $filename);
            $_SESSION['current_user']['profile_image'] = $filename;

            return ['success' => true, 'imageUrl' => $filename];

        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function removeProfileImage()
    {
        $userId = $_SESSION['current_user']['id'];
        $currentUser = $this->patientRepository->getUserById($userId);

        if (!$currentUser) {
            return ['success' => false, 'message' => 'User not found'];
        }

        $uploadDir = dirname(APPROOT) . '/public/uploads/';
        if ($currentUser['profile_image'] && $currentUser['profile_image'] !== 'default_profile.jpg') {
            $imagePath = $uploadDir . $currentUser['profile_image'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $updated = $this->patientRepository->updateUserProfileImage($userId, 'default_profile.jpg');
        if ($updated) {
            $_SESSION['current_user']['profile_image'] = 'default_profile.jpg';
            return ['success' => true, 'message' => 'Profile image removed successfully'];
        }

        return ['success' => false, 'message' => 'Failed to update database'];
    }


    public function getUserProfile()
    {
  

        $userId = $_SESSION['current_user']['id'];
        $user = $this->patientRepository->getUserById($userId);

        if (!$user) {
            return ['redirect' => 'auth/login'];
        }

        return ['user' => $user];
    }
}
