<?php
class Patient extends Controller
{
    private $db;
    public function __construct()
    {
 
        $this->model('UserModel');
        $this->db = new Database();
    }

 public function index()
    {
        $this->view('pages/home');
    }

//     public function doctorprofile($id) {
//     $user = $_SESSION['current_user'] ?? null;

//     if (!$user) {
//         setMessage('error', "You need to register first");
//         redirect("pages/register");
//     }

//     $doctor = $this->db->columnFilter('doctor_view', 'user_id', $id);
//     if (!$doctor) {
//         die('Doctor not found');
//     }

//     $time = $this->db->columnFilter('timeslots', 'user_id', $id);
//     if (!$time) {
//         die('Doctor timeslot not found');
//     }

//     // Selected date (default: today)
//     $selectedDate = $_GET['date'] ?? date('Y-m-d');

//     // Generate all slots from doctor's start to end time (20 min intervals)
//     $slots = [];
//     $current = strtotime($time['start_time']);
//     $endTime = strtotime($time['end_time']);

//     while ($current < $endTime) {
//         $slots[] = date("H:i:s", $current);
//         $current = strtotime("+20 minutes", $current);
//     }

//     // Get booked slots for this timeslot_id
//     $booked = $this->db->columnFilterAll('appointment', 'timeslot_id', $time['id']);

//     $bookedTimes = [];
//     if (!empty($booked)) {
//         foreach ($booked as $appointment) {
//             $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date']));
//             if ($appointmentDate === $selectedDate) {
//                 $bookedTimes[] = $appointment['appointment_time'];
//             }
//         }
//     }

//     $availableSlots = [];
//     $now = time();
//     foreach ($slots as $slot) {
//         $slotDateTime = strtotime("$selectedDate $slot");
        
//         // Skip if slot is in the past or now OR if booked
//         if ($slotDateTime <= $now || in_array($slot, $bookedTimes)) {
//             continue;
//         }
        
//         $availableSlots[] = date("h:i A", strtotime($slot));
//     }

//     $data = [
//         'doctor' => $doctor,
//         'user' => $user,
//         'appointment_time' => $availableSlots,
//         'selected_date' => $selectedDate
//     ];

//     $this->view('pages/doctorprofile', $data);
// }

public function doctorprofile($id) {
    date_default_timezone_set('Asia/Yangon');  // Set timezone here

    $user = $_SESSION['current_user'] ?? null;

    if (!$user) {
        setMessage('error',"You need to register first");
        redirect("pages/register");
    }

    $doctor = $this->db->columnFilter('doctor_view', 'user_id', $id);
    if (!$doctor) {
        die('Doctor not found');
    }

    $time = $this->db->columnFilter('timeslots', 'user_id', $id);
    if (!$time) {
        die('Doctor timeslot not found');
    }

    // Selected date (default: today)
    $selectedDate = $_GET['date'] ?? date('Y-m-d');

    // Generate available slots
    $slots = [];
    $current = strtotime($time['start_time']);
    $endTime = strtotime($time['end_time']);
    $slotDuration = 20 * 60; // 20 minutes in seconds
    $lastStartTime = $endTime - $slotDuration;

    while ($current <= $lastStartTime) {
        $slots[] = date("H:i:s", $current);
        $current = strtotime("+20 minutes", $current);
    }

    // Get booked slots for the selected date
    $booked = $this->db->columnFilterAll('appointment', 'timeslot_id', $time['id']);

    $bookedTimes = [];
    if (!empty($booked)) {
        foreach ($booked as $appointment) {
            $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date']));
            if ($appointmentDate === $selectedDate) {
                $bookedTimes[] = $appointment['appointment_time'];
            }
        }
    }

    // Filter available slots
    $availableSlots = [];
    $now = new DateTime();
    $selectedDateObj = new DateTime($selectedDate);

    foreach ($slots as $slot) {
        $slotDateTime = DateTime::createFromFormat('Y-m-d H:i:s', $selectedDate . ' ' . $slot);

        if (!$slotDateTime) {
            $slotDateTime = new DateTime($selectedDate . ' ' . $slot);
        }

        // Skip past slots if selected date is today
        if ($selectedDateObj->format('Y-m-d') === $now->format('Y-m-d') && $slotDateTime <= $now) {
            continue;
        }

        // Skip booked slots
        if (in_array($slot, $bookedTimes)) {
            continue;
        }

        $availableSlots[] = $slotDateTime->format("h:i A");
    }

    $data = [
        'doctor' => $doctor,
        'user' => $user,
        'appointment_time' => $availableSlots,
        'selected_date' => $selectedDate
    ];

    $this->view('pages/doctorprofile', $data);
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