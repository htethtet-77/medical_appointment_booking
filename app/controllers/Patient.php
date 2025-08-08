<?php
require_once APPROOT . '/middleware/authMiddleware.php';

class Patient extends Controller
{
    private $db;

    public function __construct()
    {
        AuthMiddleware::patientOnly();
        $this->model('UserModel');
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('pages/home');
    }

    public function doctorprofile($id)
    {
        date_default_timezone_set('Asia/Yangon');
        require_once APPROOT . '/helpers/appointment_helper.php';

        $user = $_SESSION['current_user'] ?? redirectWithMessage('pages/register', 'error', 'You need to register first');
        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $id) ?? die('Doctor not found');
        $time = $this->db->columnFilter('timeslots', 'user_id', $id) ?? die('Doctor timeslot not found');

        $selectedDate = $_GET['date'] ?? date('Y-m-d');
        $appointments = $this->db->columnFilterAll('appointment', 'timeslot_id', $time['id']) ?? [];

        $slots = getAvailableSlots($time['start_time'], $time['end_time']);
        $booked = getBookedTimes($appointments, $selectedDate);
        $availableSlots = array_map(fn($s) => (new DateTime($s))->format('h:i A'), filterFutureAvailableSlots($slots, $booked, $selectedDate));

        $this->view('pages/doctorprofile', compact('doctor', 'user', 'availableSlots', 'selectedDate'));
    }

    public function doctors()
    {
        $doctors = $this->db->readAll('doctor_view');
        $this->view('pages/doctors', compact('doctors'));
    }

    public function userprofile()
    {
        if (!isLoggedIn()) redirect('auth/login');

        $userId = $_SESSION['current_user']['id'];
        $user = $this->db->getById('users', $userId) ?? redirect('auth/login');

        $this->view('pages/userprofile', compact('user'));
    }

    public function uploadProfileImage()
    {
        if (!$this->checkAuthAndRequest('POST')) return;

        $file = $_FILES['profile_image'] ?? null;
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            $this->jsonResponse(false, 'No file uploaded or upload error');
            return;
        }

        if ($file['size'] > 5 * 1024 * 1024) {
            $this->jsonResponse(false, 'File size must be less than 5MB.');
            return;
        }

        $uploadDir = dirname(APPROOT) . '/public/uploads/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $userId = $_SESSION['current_user']['id'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = "profile_{$userId}_" . time() . ".$ext";
        $filePath = $uploadDir . $filename;

        // Remove old image
        $currentUser = $this->db->getById('users', $userId);
        if ($currentUser && $currentUser['profile_image'] !== 'default_profile.jpg') {
            $oldPath = $uploadDir . $currentUser['profile_image'];
            if (file_exists($oldPath)) unlink($oldPath);
        }

        if (move_uploaded_file($file['tmp_name'], $filePath)) {
            $this->db->update('users', $userId, ['profile_image' => $filename]);
            $_SESSION['current_user']['profile_image'] = $filename;
            $this->jsonResponse(true, null, ['imageUrl' => $filename]);
        } else {
            $this->jsonResponse(false, 'Failed to upload file');
        }
    }

    public function removeProfileImage()
    {
        if (!$this->checkAuthAndRequest('POST')) return;

        $userId = $_SESSION['current_user']['id'];
        $currentUser = $this->db->getById('users', $userId);

        if (!$currentUser) {
            $this->jsonResponse(false, 'User not found');
            return;
        }

        $uploadDir = dirname(APPROOT) . '/public/uploads/';
        if ($currentUser['profile_image'] !== 'default_profile.jpg') {
            $imagePath = $uploadDir . $currentUser['profile_image'];
            if (file_exists($imagePath)) unlink($imagePath);
        }

        $updated = $this->db->update('users', $userId, ['profile_image' => 'default_profile.jpg']);
        if ($updated) {
            $_SESSION['current_user']['profile_image'] = 'default_profile.jpg';
            $this->jsonResponse(true, 'Profile image removed successfully');
        } else {
            $this->jsonResponse(false, 'Failed to update database');
        }
    }

    // Helpers

    private function checkAuthAndRequest($method)
    {
        if (!isLoggedIn()) {
            $this->jsonResponse(false, 'User not authenticated');
            return false;
        }
        if ($_SERVER['REQUEST_METHOD'] !== $method) {
            $this->jsonResponse(false, 'Invalid request method');
            return false;
        }
        return true;
    }

    private function jsonResponse($success, $message = null, $data = [])
    {
        echo json_encode(array_merge(['success' => $success, 'message' => $message], $data));
    }
}

// Global helper functions

function isLoggedIn()
{
    return !empty($_SESSION['current_user']['id']);
}

function redirectWithMessage($url, $type, $message)
{
    setMessage($type, $message);
    redirect($url);
}
