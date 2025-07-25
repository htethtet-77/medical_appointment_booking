<?php
class Appointment extends Controller
{

    private $db;
    public function __construct()
    {
        $this->model("AppointmentModel");
        $this->model("TimeslotModel"); 
        $this->db = new Database();
    }
  public function appointmentform()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $date=$_POST['date'];
        $time=$_POST['time'];
        $reason=$_POST['reason'];
        $timeslot=new TimeslotModel();
        $timeslot->setDate($date);
        $timeslot->setTime($time);
        $timeslot->setIsAvailable(1);
        $timeslot->setUserId($_POST['doc']);
        $time=$this->db->create('timeslots',$timeslot->toArray());
        //   if (!$time) {
        //     setMessage('error', 'Need to add time.');
        //     redirect('pages/appointmentform');
        //     return;
        // }
        var_dump($time);
        die();
        $appointmentModel =new AppointmentModel();

        $appointmentModel->setUserId($_SESSION['user_id']);
        $appointmentModel->setTimeslotId($time);
        $appointmentModel->setReason($reason);
        $appointmentModel->setCreatedAt(date("Y-m-d H:i:s"));
        $appointmentModel->setStatusId(2); // pending

        $appointment=$this->db->create("appointment", $appointmentModel->toArray());
          if (!$appointment) {
            setMessage('error', 'Failed to create appointment.');
            redirect('pages/appointmentform');
            return;
        }
 
        // redirect('pages/appointment') ;  
     }
}


}
?>