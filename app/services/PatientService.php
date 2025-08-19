<?php
// require_once __DIR__ . '/../repositories/PatientRepository.php';
// require_once __DIR__ . '/../interfaces/PatientRepositoryInterface.php';
// require_once __DIR__ . '/../services/ImageUploadService.php';
// require_once __DIR__ . '/../interfaces/ImageUploadServiceInterface.php';
// require_once __DIR__ . '/../interfaces/PatientServiceInterface.php';
// // require_once __DIR__ . '/../services/MailerService.php';
// require_once __DIR__ . '/../libraries/Mail.php';
namespace Asus\Medical\services;
use Asus\Medical\interfaces\PatientServiceInterface;
use Asus\Medical\interfaces\ImageUploadServiceInterface;
use Asus\Medical\interfaces\PatientRepositoryInterface;
use Asus\Medical\helpers\AppointmentHelper;
use Asus\Medical\libraries\Mail;
use Exception;
use DateTime;
class PatientService implements PatientServiceInterface
{
    private PatientRepositoryInterface $patientRepository;
    private ImageUploadServiceInterface $imageUploader;


    public function __construct(
        PatientRepositoryInterface $patientRepository,
        ImageUploadServiceInterface $imageUploader

)
    {
        $this->patientRepository = $patientRepository;
        $this->imageUploader = $imageUploader;
    }

    public function getDoctorProfile($doctorId, $selectedDate = null):array
    {
        // require_once APPROOT . '/helpers/appointment_helper.php';

        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            return ['redirect' => 'pages/register', 'message' => 'You need to register first'];
        }

        $doctor = $this->patientRepository->getDoctorProfile($doctorId);
        if (!$doctor) {
            throw new Exception('Doctor not found');
        }

        $time = $this->patientRepository->getDoctorTimeslot($doctorId);
        if (!$time) {
            throw new Exception('Doctor timeslot not found');
        }

        $selectedDate = $selectedDate ?? date('Y-m-d');
        $allAppointments = $this->patientRepository->findAppointmentsByDoctorId($doctorId) ?? [];

        $slots = AppointmentHelper::getAvailableSlots($time['start_time'], $time['end_time']);
        $bookedTimes =AppointmentHelper:: getBookedTimes($allAppointments, $selectedDate);
        $availableSlots = array_map(function ($s) {
            return DateTime::createFromFormat('H:i:s', $s)->format('h:i A');
        }, AppointmentHelper::filterFutureAvailableSlots($slots, $bookedTimes, $selectedDate));



        return [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots,
            'selected_date' => $selectedDate
        ];
    }

    public function listDoctors():array
    {
        $doctors = $this->patientRepository->listDoctors();
        return ['doctors' => $doctors];
    }
/*public function uploadProfileImage(array $file): array
{
    try {
        $user = $_SESSION['current_user'] ?? null;
        if (!$user || empty($user['id'])) {
            return ['success' => false, 'message' => 'User not authenticated'];
        }
        $userId = (int)$user['id'];

        if (empty($file) || $file['error'] !== UPLOAD_ERR_OK) {
            return ['success' => false, 'message' => 'No valid file uploaded'];
        }

        // Absolute upload directory
        $uploadDir = realpath(dirname(__DIR__, 2) . '/public/uploads');
        if (!$uploadDir) {
            throw new Exception('Upload directory not found.');
        }

        // Delete old image if exists and not default
        $currentUser = $this->patientRepository->getUserById($userId);
        if ($currentUser && !empty($currentUser['profile_image']) &&
            $currentUser['profile_image'] !== 'default_profile.jpg') {

            $oldImagePath = $uploadDir . '/' . $currentUser['profile_image'];
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

        // Upload new image
        $filename = $this->imageUploader->upload(
            $file,
            $uploadDir,
            'profile_' . $userId . '_'
        );

        // Update DB & session
        $this->patientRepository->updateUserProfileImage($userId, $filename);
        $_SESSION['current_user']['profile_image'] = $filename;

        // Return public URL
       
        $imageUrl = APPROOT . '/uploads/' . $filename;

        return ['success' => true, 'imageUrl' => $imageUrl];

    } catch (Exception $e) {
        return ['success' => false, 'message' => $e->getMessage()];
    }
}

public function removeProfileImage(): array
{
    $user = $_SESSION['current_user'] ?? null;
    if (!$user || empty($user['id'])) {
        return ['success' => false, 'message' => 'User not authenticated'];
    }
    $userId = (int)$user['id'];

    $currentUser = $this->patientRepository->getUserById($userId);
    if (!$currentUser) {
        return ['success' => false, 'message' => 'User not found'];
    }

    $uploadDir = realpath(dirname(__DIR__, levels: 2) . '/public/uploads');

    if (!empty($currentUser['profile_image']) && $currentUser['profile_image'] !== 'default_profile.jpg') {
        $imagePath = $uploadDir . '/' . $currentUser['profile_image'];
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    $this->patientRepository->updateUserProfileImage($userId, 'default_profile.jpg');
    $_SESSION['current_user']['profile_image'] = 'default_profile.jpg';

    return [
        'success' => true,
        'message' => 'Profile image removed successfully',
        'imageUrl' => URLROOT . '/uploads/default_profile.jpg'
    ];
}


    public function getUserProfile(): array
    {
        $user = $_SESSION['current_user'] ?? null;
        if (!$user || empty($user['id'])) {
            return ['redirect' => 'auth/login'];
        }

        $dbUser = $this->patientRepository->getUserById((int)$user['id']);
        if (!$dbUser) {
            return ['redirect' => 'auth/login'];
        }

        return ['user' => $dbUser];
    }*/
    public function uploadProfileImage($files):array
    {
        try {
            if (!isset($files['profile_image'])) {
                return ['success' => false, 'message' => 'No file uploaded'];
            }

            $file = $files['profile_image'];
            $userId = $_SESSION['current_patient']['id'];
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
            $_SESSION['current_patient']['profile_image'] = $filename;

            return ['success' => true, 'imageUrl' => $filename];

        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function removeProfileImage():array
    {
        $userId = $_SESSION['current_patient']['id'];
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
            $_SESSION['current_patient']['profile_image'] = 'default_profile.jpg';
            return ['success' => true, 'message' => 'Profile image removed successfully'];
        }

        return ['success' => false, 'message' => 'Failed to update database'];
    }


    public function getUserProfile():array
    {
        $userId = $_SESSION['current_patient']['id'];
         if (!$userId || empty($userId['id'])) {
            return ['redirect' => 'auth/login'];
        }
        $user = $this->patientRepository->getUserById($userId);

        if (!$user) {
            return ['redirect' => 'auth/login'];
        }

        return ['user' => $user];
    }
        public function sendContactMessage($fullName, $emailAddress, $subject, $message):array
    {
        if (empty($fullName) || empty($emailAddress) || empty($subject) || empty($message)) {
            return ['success' => false, 'message' => 'All fields are required.'];
        }

        // Use the Mail class
        $mail=new Mail();
        $sent = $mail->sendContactMessage($fullName, $emailAddress, $subject, $message);

        if ($sent) {
            return ['success' => true, 'message' => 'Your message has been sent successfully.'];
        } else {
            return ['success' => false, 'message' => 'Failed to send your message. Please try again later.'];
        }
    }

}
