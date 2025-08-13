<?php
require_once __DIR__ . '/../interfaces/AdminServiceInterface.php';
require_once __DIR__ . '/../middleware/authMiddleware.php'; 
require_once __DIR__ . '/../services/AdminService.php';

class Admin extends Controller
{
    protected AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        AuthMiddleware::adminOnly();
        $this->adminService = $adminService;
  
    }
    

    public function index()
    {
        return $this->dashboard();
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
            $msg = strpos($e->getMessage(), 'upload') !== false
                ? 'Image upload failed: ' . $e->getMessage()
                : $e->getMessage();
            setMessage('error', $msg);
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
            $msg = strpos($e->getMessage(), 'upload') !== false
                ? 'Image upload failed: ' . $e->getMessage()
                : $e->getMessage();
            setMessage('error', $msg);
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
