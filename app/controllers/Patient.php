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
        
        // Replace 'doctor_id' with your actual PK column name
        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $id);
//         var_dump($id);
// var_dump($doctor);
// die;

        if (!$doctor) {
            redirect('pages/notfound');
        }

        $data = ['doctor' => $doctor];
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
 



}
?>