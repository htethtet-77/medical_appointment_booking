<?php
require_once APPROOT . '/middleware/authMiddleware.php';
require_once __DIR__ . '/../services/DoctorService.php';

class Doctor extends Controller
{
    private $doctorService;

    public function __construct()
    {
        AuthMiddleware::doctorOnly();
        $db = new Database();
        $doctorRepository = new DoctorRepository($db);
        $this->doctorService = new DoctorService($doctorRepository);

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
}
