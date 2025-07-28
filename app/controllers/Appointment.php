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
        
        $doctor = $this->db->columnFilter('doctor_view','user_id',$doctor_id); // Make sure this works
// var_dump( $doctor);
// die();
        if (!$doctor) {
            die('Doctor not found'); // or redirect to an error page
        }

        $data = [
            'doctor' => $doctor
        ];

        $this->view('pages/appointmentform', $data);
    }
    public function book(){
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $doctorId     = $_POST['doctorId'] ?? null;
        $patientEmail = $_POST['email'] ?? null;
        $reason       = $_POST['reason'] ?? '';
            // $user_id=$_POST['user_id'];
var_dump($_POST); 

            // Get doctor details from doctor_view by doctor user_id
            $doctor = $this->db->columnFilter('doctor_view', 'user_id', $doctorId);
            // var_dump($doctor);
            // var_dump($patientEmail);
            // var_dump($reason);
// exit;

            if (!$doctor) {
                die('Doctor not found');
            }
            // else{
            //     var_dump($doctor);
            // }

            // Get patient user by email from user table (not doctor_view)
            $patient = $this->db->columnFilter('users', 'email', $patientEmail);
            // var_dump($patient);
            // exit;
            if (!$patient) {
                die('Patient not found');
            }

            $appointment = new AppointmentModel();
            $appointment->setCreatedAt(date('Y-m-d H:i:s'));
            $appointment->setReason($reason);
            $appointment->setTimeslotId($doctor['timeslot_id']);
            $appointment->setUserId($patient['id']);  // patient id
            $appointment->setDoctorId($doctor['doctor_id']);      // doctor id
            $appointment->setStatusId(2);

            $appointment_id = $this->db->create('appointment', $appointment->toArray());

            if (!$appointment_id) {
                setMessage('error', 'Failed to create appointment.');
                redirect('pages/appointmentform');
                return;
            }

            redirect("pages/appointment");
        }

}

        public function appointmentlist(){
            $appointment=$this->db->readAll('doctor_view');
            $data=[
                'appointment'=>$appointment
            ];
            $this->view("pages/appointment",$data);
        }
}
?>