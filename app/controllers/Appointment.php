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
      

        $doctor = $this->db->columnFilter('doctor_view','user_id',$doctor_id);
        if (!$doctor) {
            die('Doctor not found');
        }

        $data = [
            'doctor' => $doctor,
            'user' => $user,
        ];

        $this->view('pages/appointmentform', $data);
    }

    public function book(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $doctorId     = $_POST['doctorId'] ?? null;
        $patientEmail = $_POST['email'] ?? null;
        $reason       = $_POST['reason'] ?? '';

           // Validate required fields
        if (!$doctorId || empty($reason)) {
            setMessage('error', 'Please fill in all required fields.');
            redirect("appointment/appointmentform/$doctorId");
            return;
        }

            // Get doctor details from doctor_view by doctor user_id
            $doctor = $this->db->columnFilter('doctor_view', 'user_id', $doctorId);
            if (!$doctor) {
                die('Doctor not found');
            }
          
            // Get patient user by email from user table (not doctor_view)
             $user = $_SESSION['current_user'] ?? null;
             // Get logged-in patient user
            $user = $_SESSION['current_user'] ?? null;
            if (!$user) {
                setMessage('error', 'Please log in to book an appointment.');
                redirect("pages/login?doctor_id=$doctorId");
                return;
            }
            $patient = $this->db->columnFilter('users', 'id', $user['id']);
         
            if (!$patient) {
                die('Patient not found');
            }

            $appointment = new AppointmentModel();
            $appointment->setCreatedAt(date('Y-m-d H:i:s'));
            $appointment->setReason($reason);
            $appointment->setTimeslotId($doctor['timeslot_id']);
            $appointment->setAppointmentTime()
            $appointment->setUserId($patient['id']);  // patient id
            $appointment->setDoctorId($doctor['doctor_id']);      // doctor id
            $appointment->setStatusId(2);

            $appointment_id = $this->db->create('appointment', $appointment->toArray());

            if (!$appointment_id) {
                setMessage('error', 'Failed to create appointment.');
                redirect('pages/appointmentform');
                return;
            }
            setMessage('success', 'Appointment booked successfully.');
            redirect("appointment/appointmentlist");
        } else {
        // If someone accesses book() without POST, redirect somewhere
        redirect('pages/home');
    }
}

       public function appointmentlist() {
        // session_start();
            $user = $_SESSION['current_user'] ;
            if (!isset($user)) {
                redirect('pages/appointment');
                exit;
            }

            $appointments = $this->db->columnFilterAll('appointment_view', 'patient_id', $user['id']);
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
            if($appointment['status_id']===3){
                setMessage('error',"Appointment Already Rejected by doctor");
                redirect("appointment/appointmentlist");
                return;
            }
            $deleted=$this->db->delete('appointment',$appointment['id']);
             if ($deleted) {
                setMessage('success', 'Appointment cancelled successfully.');
            } else {
                setMessage('error', 'Failed to cancel appointment.');
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