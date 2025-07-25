<?php

class Pages extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('pages/login');
    }
    public function home()
    {
        $this->view('pages/home');
    }
    public function doctors()
    {
        $doctorWithUserInfo = $this->db->readAll('doctor_view');
        $data = [
            'doctors' => $doctorWithUserInfo
        ];

        $this->view('pages/doctors', $data);
    }
     public function appointment()
    {
        $this->view('pages/appointment');
    }

  public function doctorprofile($email) {
    // Replace 'doctor_id' with your actual PK column name
    $doctor = $this->db->columnFilter('doctor_view', 'email', $email);

    if (!$doctor) {
        redirect('pages/notfound');
    }

    $data = ['doctor' => $doctor];
    $this->view('pages/doctorprofile', $data);
}


    public function approve()
    {
    
        $this->view('pages/approve');
    }
    public function contactus()
    {
        $this->view('pages/contactus');
    }
     public function userprofile()
    {
        $this->view('pages/userprofile');
    }
    public function history()
    {
        $this->view('pages/history');
    }
    public function appointmentform()
    {
        $this->view('pages/appointmentform');
    }
     public function userappointment()
    {
        $this->view('pages/userappointment');
    }



    public function login()
    {
        $this->view('pages/login');
    }

    public function register()
    {
        $this->view('pages/register');
    }

    public function about()
    {
        $this->view('pages/about');
    }

    public function dashboard()
    {
        

        $this->view('pages/dashboard');
    }

}
