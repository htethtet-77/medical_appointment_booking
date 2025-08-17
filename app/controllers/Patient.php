<?php
namespace Asus\Medical\controllers;
use Asus\Medical\Middleware\AuthMiddleware;
use Asus\Medical\libraries\Controller;
use Asus\Medical\interfaces\PatientServiceInterface;
use function Asus\Medical\helpers\setMessage;

// require_once __DIR__ . '/../middleware/authMiddleware.php';
// require_once __DIR__ . '/../services/PatientService.php';
// require_once __DIR__ . '/../interfaces/PatientServiceInterface.php';


class Patient extends Controller
{
    protected PatientServiceInterface $patientService;

    // Inject PatientService, which requires PatientRepository internally
    public function __construct(PatientServiceInterface $patientService)
    {
        AuthMiddleware::allowRoles([ROLE_PATIENT]);

        $this->patientService =$patientService;
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

    // Check if AJAX request
    if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
       strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        // Return JSON only for AJAX
        header('Content-Type: application/json');
        echo json_encode([
            'appointment_time' => $result['appointment_time']
        ]);
        exit;
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
    public function sendMessage()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $fullName = trim($_POST['fullName']);
        $emailAddress = trim($_POST['emailAddress']);
        $subject = trim($_POST['subject']);
        $message = trim($_POST['message']);


        $result =$this->patientService->sendContactMessage($fullName, $emailAddress, $subject, $message);

        if ($result['success']) {
            redirect('pages/contactus');
            exit;
        } else {
            header("Location: /contact?error=" . urlencode($result['message']));
            exit;
        }
    }
}

}
