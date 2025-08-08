<?php
// require_once 'helpers/appointment_helper.php';

class Appointment extends Controller
{

    private $db;
    public function __construct()
    {
        $this->model("AppointmentModel");
        // $this->model("UserModel");
        $this->db = new Database();
    }
    // Main form loader - loads initial page and slots for default or given date
    public function appointmentform($doctor_id) {
        date_default_timezone_set('Asia/Yangon');
        require_once APPROOT .'/helpers/appointment_helper.php';

        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            setMessage('error', "You need to register first");
            return redirect("pages/register");
        }

        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $doctor_id) ?? die('Doctor not found');
        $time = $this->db->columnFilter('timeslots', 'user_id', $doctor_id) ?? die('Doctor timeslot not found');

        $selectedDate = $_GET['date'] ?? date('Y-m-d');

        $slots = getAvailableSlots($time['start_time'], $time['end_time']);
        $appointments = $this->db->columnFilterAll('appointment', 'doctor_id', $doctor_id) ?? [];
        $bookedTimes = getBookedTimes($appointments, $selectedDate);
        $availableSlots = filterFutureAvailableSlots($slots, $bookedTimes, $selectedDate);

        $this->view('pages/appointmentform', [
            'doctor' => $doctor,
            'user' => $user,
            'appointment_time' => $availableSlots,
            'selected_date' => $selectedDate
        ]);
    }



        public function book() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return redirect('pages/home');
        }

        // Sanitize and assign POST data
        $doctorId     = $_POST['doctor_id'] ?? null;
        $email        = $_POST['email'] ?? null;
        $reason       = trim($_POST['reason'] ?? '');
        $timeslot     = $_POST['timeslot'] ?? '';
        $date         = $_POST['appointment_date'] ?? null;

        // Format inputs
        $appointmentDate = $date ? date("Y-m-d", strtotime($date)) : null;
        $appointmentTime = $timeslot ? date("H:i:s", strtotime($timeslot)) : null;

        // Validate required fields
        if (!$doctorId || !$appointmentDate || !$appointmentTime || empty($reason)) {
            setMessage('error', 'Please fill in all required fields.');
            return redirect("appointment/appointmentform/$doctorId");
        }

        // Check user session
        $user = $_SESSION['current_user'] ?? null;
        if (!$user) {
            setMessage('error', 'Please log in to book an appointment.');
            return redirect('pages/login');
        }

        // Fetch doctor and patient data
        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $doctorId);
        $patient = $this->db->columnFilter('users', 'id', $user['id']);

        if (!$doctor || !$patient) {
            setMessage('error', 'Doctor or patient not found.');
            return redirect("appointment/appointmentform/$doctorId");
        }

        // Check for duplicate booking
        $appointments = $this->db->columnFilterAll('appointment', 'doctor_id', $doctorId);
        foreach ($appointments as $a) {
            if ($a['appointment_date'] === $appointmentDate && $a['appointment_time'] === $appointmentTime) {
                setMessage('error', 'This timeslot is already booked. Please choose another.');
                return redirect("appointment/appointmentform/$doctorId");
            }
        }

        // Call stored procedure to create appointment
        $success = $this->db->callProcedure('book_appointment', [
            $doctorId,
            $patient['id'],
            $doctor['timeslot_id'],
            $appointmentDate,
            $appointmentTime,
            $reason,
            2 // status_id: pending
        ]);

        if (!$success) {
            setMessage('error', 'Failed to create appointment.');
            return redirect("appointment/appointmentform/$doctorId");
        }

        setMessage('success', 'Appointment booked successfully.');
        return redirect('appointment/appointmentlist');
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
        public function delete($id) 
        {
            $appointment = $this->db->columnFilter('appointment', 'id', $id);

            if (!$appointment) {
                setMessage('error', 'Appointment not found.');
                return redirect('appointment/appointmentlist');
            }

            if ($appointment['status_id'] === 1) {
                setMessage('error', 'Appointment already confirmed.');
                return redirect('appointment/appointmentlist');
            }

            if ($appointment['status_id'] === 3) {
                setMessage('error', 'Appointment already rejected by doctor.');
                return redirect('appointment/appointmentlist');
            }

            // Call stored procedure instead of direct delete
            $deleted = $this->db->callProcedure('delete_appointment', [$id]);

            setMessage($deleted ? 'success' : 'error', $deleted ? 'Appointment cancelled successfully.' : 'Failed to cancel appointment.');
            redirect('appointment/appointmentlist');
        }

    //appointment confirm by doctor
        public function confirm($appointment_id){
            $id=$this->db->columnFilter('appointment','id',$appointment_id);
  
            $appointment = new AppointmentModel();
                $appointment->created_at=$id['created_at'];
                $appointment->reason=$id['reason'];
                $appointment->timeslot_id=$id['timeslot_id'];
                $appointment->appointment_date=$id['appointment_date'];
                $appointment->appointment_time=$id['appointment_time'];
                $appointment->user_id=$id['user_id'];  // patient id
                $appointment->doctor_id=$id['doctor_id'];      // doctor id
                $appointment->status_id=1;
            $updated= $this->db->update('appointment',$id['id'],$appointment->toArray());
                if(!$updated){
                    setMessage('error','Something Wrong');
                    return;
                }
                setMessage('success','Appointment confirmed successfully');
                redirect("doctor/dash");
        }
        //appointment reject by doctor
        public function  reject($appointment_id){
            $id=$this->db->columnFilter('appointment','id', $appointment_id);
            $appointment = new AppointmentModel();
                $appointment->created_at=$id['created_at'];
                $appointment->reason=$id['reason'];
                $appointment->timeslot_id=$id['timeslot_id'];
                $appointment->appointment_date=$id['appointment_date'];
                $appointment->appointment_time=$id['appointment_time'];
                $appointment->user_id=$id['user_id'];  // patient id
                $appointment->doctor_id=$id['doctor_id'];      // doctor id
                $appointment->status_id=3;
                $updated= $this->db->update('appointment',$id['id'],$appointment->toArray());
                if(!$updated){
                    setMessage('error','Something Wrong');
                    return;
                }
                setMessage('success','Appointment reject successfully');
                redirect("doctor/dash");

        }

        
}
?>