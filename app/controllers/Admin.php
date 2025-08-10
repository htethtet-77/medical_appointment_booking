<?php
require_once __DIR__ . '/../services/AdminService.php';
require_once __DIR__ . '/../helpers/UserValidator.php'; 
require_once __DIR__ . '/../middleware/authMiddleware.php'; 

class Admin extends Controller
{
    protected AdminService $adminService;

    public function __construct()
    {
        // Restrict to admin users only
        AuthMiddleware::adminOnly();
        $db=new Database();
        $adminRepo = new AdminRepository($db);
        $validator = new UserValidator([]);
        $imageUploader = new ImageUploadService();

        $this->adminService = new AdminService($adminRepo, $validator, $imageUploader);
    }

    public function index()
    {
        $this->dashboard();
    }

    public function adddoctor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->view('admin/adddoctor');
        }

        try {
            $userId = $this->adminService->addDoctor($_POST, $_FILES['image'] ?? []);
            setMessage('success', "Doctor added successfully! (ID: {$userId})");
            redirect('admin/doctorlist');
        } catch (Exception $e) {
            // You can customize messages based on exception type or message content:
            if (strpos($e->getMessage(), 'upload') !== false) {
                setMessage('error', 'Image upload failed: ' . $e->getMessage());
            } else {
                setMessage('error', $e->getMessage());
            }
            redirect('admin/adddoctor');
        }

    }

    public function doctorlist()
    {
        $doctors = $this->adminService->getAllDoctors();
        $this->view('admin/doctorlist', ['doctors' => $doctors]);
    }

    public function deletedoctor()
    {
        $user_id = $_POST['user_id'] ?? null;

        try {
            $this->adminService->deleteDoctor($user_id);
            setMessage('success', 'Doctor deleted successfully!');
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }

        redirect('admin/doctorlist');
    }

    public function editdoctor($user_id)
    {
        try {
            $data = $this->adminService->getDoctorDetails($user_id);
            $this->view('admin/editdoctor', $data);
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
            redirect('admin/doctorlist');
        }
    }

    public function updatedoctor()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('admin/doctorlist');
        }

        try {
            $this->adminService->updateDoctor($_POST, $_FILES['image'] ?? []);
            setMessage('success', 'Doctor updated successfully!');
        } catch (Exception $e) {
            setMessage('error', 'Image upload failed: ' . $e->getMessage());
        } catch (Exception $e) {
            setMessage('error', $e->getMessage());
        }

        redirect('admin/doctorlist');
    }

    public function patientlist()
    {
        $patients = $this->adminService->getPatients();
        $this->view('admin/patientlist', ['user' => $patients]);
    }

    public function appointmentview()
    {
        $appointments = $this->adminService->getAppointments();
        $this->view('admin/appointmentview', ['appointments' => $appointments]);
    }

    public function dashboard()
    {
        $data = $this->adminService->getDashboardData();
        $this->view('admin/dashboard', $data);
    }
}
