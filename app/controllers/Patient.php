<?php
require_once __DIR__ . '/../middleware/authMiddleware.php';
require_once __DIR__ . '/../services/PatientService.php';

class Patient extends Controller
{
    protected $patientService;

    // Inject PatientService, which requires PatientRepository internally
    public function __construct()
    {
        AuthMiddleware::patientOnly();
        $db = new Database();
        $repository = new PatientRepository($db);
        $appointmentRepository = new AppointmentRepository($db);
        $imageUploader = new ImageUploadService();
        $appointmentService = new AppointmentService($appointmentRepository);

        $this->patientService = new PatientService($repository,$imageUploader,$appointmentService);
    }

    public function index()
    {
        $this->view('pages/home');
    }

    public function doctorprofile($id)
    {
        $result = $this->patientService->getDoctorProfile($id, $_GET['date'] ?? null);
        if (isset($result['redirect'])) {
            setMessage('error', $result['message']);
            return redirect($result['redirect']);
        }
        $this->view('pages/doctorprofile', $result);
    }

    public function doctors()
    {
        $data = $this->patientService->listDoctors();
        $this->view('pages/doctors', $data);
    }

    public function uploadProfileImage()
    {
        $response = $this->patientService->uploadProfileImage($_FILES);
        echo json_encode($response);
    }

    public function removeProfileImage()
    {
        $response = $this->patientService->removeProfileImage();
        echo json_encode($response);
    }

    public function userprofile()
    {
        $result = $this->patientService->getUserProfile();
        if (isset($result['redirect'])) {
            return redirect($result['redirect']);
        }
        $this->view('pages/userprofile', $result);
    }
}
