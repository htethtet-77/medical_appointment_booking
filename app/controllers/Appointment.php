<?php
class Appointment extends Controller
{

    private $db;
    public function __construct()
    {
        $this->model("AppointmentModel");
        // $this->model("UserModel");
        $this->db = new Database();
    }
    public function appointmentform($doctor_id) {
        $user = $_SESSION['current_user'] ?? null;

        // Get doctor
        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $doctor_id);
        if (!$doctor) {
            die('Doctor not found');
        }

        // Get doctor time slots
        $time = $this->db->columnFilter('timeslots', 'user_id', $doctor_id);
        if (!$time) {
            die('Doctor timeslot not found');
        }
          // Selected date (default: today)
        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        // Generate available slots
        $slots = [];
        $current = strtotime($time['start_time']);
        $endTime = strtotime($time['end_time']);

        while ($current < $endTime) {
            $slots[] = date("H:i:s", $current); // store in 24h format
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
        foreach ($slots as $slot) {
            $slotDateTime = strtotime("$selectedDate $slot");
            if ($slotDateTime > time() && !in_array($slot, $bookedTimes)) {
                $availableSlots[] = date("h:i A", strtotime($slot)); // show in 12h format
            }
        }

        $data = [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots
        ];

        $this->view('pages/appointmentform', $data);
    }

    public function book() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $doctorId       = $_POST['doctor_id'] ?? null;
        $patientEmail   = $_POST['email'] ?? null;
        $reason         = trim($_POST['reason'] ?? '');
        $timeslot       = $_POST['timeslot'] ?? '';
        $date           = $_POST['appointment_date'] ?? null;

        // Convert to proper DB formats
        $appointmentDate = date("Y-m-d", strtotime($date)); 
        $timeslot_24h    = date("H:i:s", strtotime($timeslot));

        // Validate required fields
        if (!$doctorId || !$appointmentDate || !$timeslot_24h || empty($reason)) {
            setMessage('error', 'Please fill in all required fields.');
            redirect("appointment/appointmentform/$doctorId");
            return;
        }

        // Get doctor details
        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $doctorId);
        if (!$doctor) {
            setMessage('error', 'Doctor not found.');
            redirect("appointment/appointmentform/$doctorId");
            return;
        }

        // Check user login
        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            setMessage('error', 'Please log in to book an appointment.');
            redirect("pages/login");
            return;
        }

        $patient = $this->db->columnFilter('users', 'id', $user['id']);
        if (!$patient) {
            setMessage('error', 'Patient not found.');
            redirect("appointment/appointmentform/$doctorId");
            return;
        }

        // Check if slot already booked for this doctor on selected date
        $booked = $this->db->columnFilterAll('appointment', 'doctor_id', $doctorId);

        $isAlreadyBooked = false;
        if (!empty($booked)) {
            foreach ($booked as $appointment) {
                if (
                    $appointment['appointment_date'] === $appointmentDate &&
                    $appointment['appointment_time'] === $timeslot_24h
                ) {
                    $isAlreadyBooked = true;
                    break;
                }
            }
        }

        if ($isAlreadyBooked) {
            setMessage('error', 'This timeslot is already booked. Please choose another.');
            redirect("appointment/appointmentform/$doctorId");
            exit;
        }

        // Create appointment
        $appointment = new AppointmentModel();
        $appointment->setCreatedAt(date('Y-m-d H:i:s'));
        $appointment->setReason($reason);
        $appointment->setTimeslotId($doctor['timeslot_id']);
        $appointment->setAppointmentDate($appointmentDate);
        $appointment->setAppointmentTime($timeslot_24h);
        $appointment->setUserId($patient['id']);  // patient id
        $appointment->setDoctorId($doctorId);     // doctor id
        $appointment->setStatusId(2);             // pending

        $appointment_id = $this->db->create('appointment', $appointment->toArray());

        if (!$appointment_id) {
            setMessage('error', 'Failed to create appointment.');
            redirect("appointment/appointmentform/$doctorId");
            return;
        }

        setMessage('success', 'Appointment booked successfully.');
        redirect("appointment/appointmentlist");

    } else {
        // If not POST request
        redirect('pages/home');
    }
}


       public function appointmentlist() {
        // session_start();
            if (!isset($_SESSION['current_user']) || !is_array($_SESSION['current_user'])) {
                redirect('pages/appointment');
                exit;
            }
            $user = $_SESSION['current_user'] ?? '' ;

            $appointments = $this->db->columnFilterAll('appointment_view', 'patient_id', $user['id']);
            // var_dump($appointments);
            // exit;
            $data = [
                'appointments' => $appointments,
                'user'=>$user,
                ];
            $this->view("pages/appointment", $data);
        }

//patient appointment cancel
        public function delete($id){
            $appointment=$this->db->columnFilter('appointment','id',$id);
            if($appointment['status_id']===1){
                setMessage('error',"Appointment Already Confirmed");
                redirect("appointment/appointmentlist");
                return;
            }
            elseif($appointment['status_id']===3){
                setMessage('error',"Appointment Already Rejected by doctor");
                redirect("appointment/appointmentlist");
                return;
            }else{
            $deleted=$this->db->delete('appointment',$appointment['id']);
             if ($deleted) {
                setMessage('success', 'Appointment cancelled successfully.');
            } else {
                setMessage('error', 'Failed to cancel appointment.');
            }
        }
            
            redirect('appointment/appointmentlist');


        }
    //doctor appointment confirm
        public function confirm($appointment_id){
            $id=$this->db->columnFilter('appointment','id',$appointment_id);
    // var_dump($id);
    // exit;
            $appointment = new AppointmentModel();
                $appointment->setCreatedAt($id['created_at']);
                $appointment->setReason($id['reason']);
                $appointment->setTimeslotId($id['timeslot_id']);
                $appointment->setAppointmentDate($id['appointment_date']);
                $appointment->setAppointmentTime($id['appointment_time']);
                $appointment->setUserId($id['user_id']);  // patient id
                $appointment->setDoctorId($id['doctor_id']);      // doctor id
                $appointment->setStatusId(1);
            $updated= $this->db->update('appointment',$id['id'],$appointment->toArray());
                if(!$updated){
                    setMessage('error','Something Wrong');
                    return;
                }
                // var_dump($updated);
                // exit;
                setMessage('success','Appointment confirmed successfully');
                redirect("doctor/dash");
        }
        //doctor appointment reject
        public function  reject($appointment_id){
            $id=$this->db->columnFilter('appointment','id', $appointment_id);
            $appointment = new AppointmentModel();
                $appointment->setCreatedAt($id['created_at']);
                $appointment->setReason($id['reason']);
                $appointment->setTimeslotId($id['timeslot_id']);
                $appointment->setAppointmentDate($id['appointment_date']);
                $appointment->setAppointmentTime($id['appointment_time']);
                $appointment->setUserId($id['user_id']);  // patient id
                $appointment->setDoctorId($id['doctor_id']);      // doctor id
                $appointment->setStatusId(3);
                $updated= $this->db->update('appointment',$id['id'],$appointment->toArray());
                if(!$updated){
                    setMessage('error','Something Wrong');
                    return;
                }
                // var_dump($updated);
                // exit;
                setMessage('success','Appointment reject successfully');
                redirect("doctor/dash");

        }

        
}
?>