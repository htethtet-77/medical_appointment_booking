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
        $now = time();

    foreach ($slots as $slot) {
    // Make a datetime for the slot with selected date + slot time (24h)
    $slotDateTime = strtotime("$selectedDate $slot");

    // If slot date is today, exclude past or current time
    if ($selectedDate === date('Y-m-d') && $slotDateTime <= $now) {
        continue; // skip past slots
    }

    // Skip booked slots
    if (in_array($slot, $bookedTimes)) {
        continue;
    }

    // Add slot in 12-hour format for display
    $availableSlots[] = date("h:i A", strtotime($slot));
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