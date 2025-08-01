<?php
class Patient extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('UserModel');
        $this->db = new Database();
    }
      public function doctorprofile($id) {
        $user = $_SESSION['current_user'] ?? null;
        if(!$user){
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

        // Generate available slots
        $slots = [];
        $current = strtotime($time['start_time']);
        $endTime = strtotime($time['end_time']);

        while ($current < $endTime) {
            $slots[] = date("H:i:s", $current); // store in 24h format
            $current = strtotime("+20 minutes", $current);
        }

        // Get today's booked slots
        $today = date('Y-m-d');
        $booked = $this->db->columnFilterAll('appointment', 'timeslot_id', $time['id']);

        $bookedTimes = [];
        if (!empty($booked)) {
            foreach ($booked as $appointment) {
                $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date'] ?? $appointment['created_at']));
                if ($appointmentDate === $today) {
                    $bookedTimes[] = $appointment['appointment_time'];
                }
            }
        }

        // Filter slots
        $availableSlots = [];
        foreach ($slots as $slot) {
            $slotDateTime = strtotime("$today $slot");
            if ($slotDateTime > time() && !in_array($slot, $bookedTimes)) {
                $availableSlots[] = date("h:i A", strtotime($slot)); // display in 12h format
            }
        }

        $data = [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots
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