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
        if (!$user) {
            setMessage('error',"You need to register first");
            redirect("pages/register");
            return;
        }
        // Replace 'doctor_id' with your actual PK column name
        $doctor = $this->db->columnFilter('doctor_view', 'user_id', $id);
        
        
        if (!$doctor) {
            redirect('pages/notfound');
        }

        $data = [
            'doctor' => $doctor,
            'user'=>$user
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