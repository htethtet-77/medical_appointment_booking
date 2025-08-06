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
        require_once APPROOT .'/helpers/appointment_helper.php';

        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            setMessage('error', "You need to register first");
            return redirect("pages/register");
        }

        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $id);
        if (!$doctor) die('Doctor not found');

        $time = $this->db->columnFilter('timeslots', 'user_id', $id);
        if (!$time) die('Doctor timeslot not found');

        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        $allAppointments = $this->db->columnFilterAll('appointment', 'timeslot_id', $time['id']) ?? [];

        $slots = getAvailableSlots($time['start_time'], $time['end_time']);
        $bookedTimes = getBookedTimes($allAppointments, $selectedDate);
        $availableSlots = array_map(function ($s) {
            return DateTime::createFromFormat('H:i:s', $s)->format('h:i A');
        }, filterFutureAvailableSlots($slots, $bookedTimes, $selectedDate));

        $this->view('pages/doctorprofile', [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots,
            'selected_date' => $selectedDate
        ]);
    }


      public function doctors()
    {
        $doctorWithUserInfo = $this->db->readAll('doctor_view');
        $data = [
            'doctors' => $doctorWithUserInfo
        ];

        $this->view('pages/doctors', $data);
    }

    public function userprofile(){
        if (!isset($_SESSION['current_user'])) {
            setMessage('error', 'You must be logged in to view your profile.');
            redirect('pages/login');
            return;
        }

        $user = $_SESSION['current_user'];
        $this->view('pages/userprofile', ['user' => $user]);
        }



}
?>