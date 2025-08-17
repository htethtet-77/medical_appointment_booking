<?php
namespace Asus\Medical\Controllers;
use Asus\Medical\Interfaces\AdminServiceInterface;
use Asus\Medical\Middleware\AuthMiddleware;
use Asus\Medical\Middleware\CsrfMiddleware;
use Asus\Medical\Middleware\PostMiddleware;

use Asus\Medical\libraries\Controller;
use function Asus\Medical\helpers\setMessage;
use Exception;


class Admin extends Controller
{
    protected AdminServiceInterface $adminService;

    public function __construct(AdminServiceInterface $adminService)
    {
        AuthMiddleware::allowRoles([ROLE_ADMIN]);
        // PostMiddleware::protect();
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
//    public function adddoctor()
// {
//     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
//         // Generate CSRF token
//         $csrf_token = CsrfMiddleware::generateToken();
//         return $this->view('admin/adddoctor', ['csrf_token' => $csrf_token]);
//     }

//     try {
//         // Validate CSRF token on POST
//         CsrfMiddleware::validateToken();

//         $userId = $this->adminService->addDoctor($_POST, $_FILES['image'] ?? []);
//         setMessage('success', "Doctor added successfully! (ID: {$userId})");
//         redirect('admin/doctorlist');
//     } catch (Exception $e) {
//         $msg = strpos($e->getMessage(), 'upload') !== false
//             ? 'Image upload failed: ' . $e->getMessage()
//             : $e->getMessage();
//         setMessage('error', $msg);
//         redirect('admin/adddoctor');
//     }
// }

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
        // Make sure user is admin (AuthMiddleware already runs in constructor)
        $doctorData = $this->adminService->getDoctorDetails($user_id);

        // Generate CSRF token for this form only
        $doctorData['csrf_token'] = CsrfMiddleware::generateToken();

        // Pass data to edit view
        $this->view('admin/editdoctor', $doctorData);

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
        // Validate CSRF token from this form only
       CsrfMiddleware::validateToken();

        // Call service to update doctor
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




    // public function editdoctor($user_id)
    // {
    //     try {
    //         $data = $this->adminService->getDoctorDetails($user_id);
    //         $this->view('admin/editdoctor', $data);
    //     } catch (Exception $e) {
    //         setMessage('error', $e->getMessage());
    //         redirect('admin/doctorlist');
    //     }
    // }
/*public function updatedoctor()
{

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('admin/doctorlist');
    }

    // Only validate for this form
    CsrfMiddleware::validateToken();

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
}*/



    // public function updatedoctor()
    // {
    //     if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    //         redirect('admin/doctorlist');
    //     }

    //     try {
    //         $this->adminService->updateDoctor($_POST, $_FILES['image'] ?? []);
    //         setMessage('success', 'Doctor updated successfully!');
    //     } catch (Exception $e) {
    //         $msg = strpos($e->getMessage(), 'upload') !== false
    //             ? 'Image upload failed: ' . $e->getMessage()
    //             : $e->getMessage();
    //         setMessage('error', $msg);
    //     }

    //     redirect('admin/doctorlist');
    // }

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
?>
