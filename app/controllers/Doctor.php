<?php
namespace Asus\Medical\controllers;
use Asus\Medical\interfaces\DoctorServiceInterface;
use Asus\Medical\Middleware\AuthMiddleware;
use Asus\Medical\libraries\Controller;
use Exception;
// require_once APPROOT . '/middleware/authMiddleware.php';
// require_once __DIR__ . '/../services/DoctorService.php';
// require_once __DIR__ . '/../interfaces/DoctorServiceInterface.php';


class Doctor extends Controller
{
     protected DoctorServiceInterface $doctorService;

    public function __construct(DoctorServiceInterface $doctorService )
    {
        AuthMiddleware::allowRoles([ROLE_DOCTOR]);
        $this->doctorService = $doctorService;
    }

    public function dash()
    {
        $doctorUserId = $_SESSION['current_doctor']['user_id'];
        $data = $this->doctorService->getDoctorDashboardData($doctorUserId);
        $this->view('doctor/dash', $data);
    }

    public function profile()
    {
        $this->view('doctor/profile');
    }

   public function changePassword()
{
    if (!isset($_SESSION['current_doctor'])) {
        redirect('auth/login');
    }

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        redirect('doctor/profile');
    }

    $doctorId = $_SESSION['current_doctor']['user_id'];
  
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');
  

    try {
        $this->doctorService->changePassword($doctorId, $currentPassword, $newPassword, $confirmPassword);

        $_SESSION['password_success'] = 'Password updated successfully!';
        redirect('doctor/profile');
    } catch (Exception $e) {
        $_SESSION['password_error'] = $e->getMessage();
        redirect('doctor/profile');
    }
}

}
