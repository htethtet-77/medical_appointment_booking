<?php
namespace Asus\Medical\controllers;
use Asus\Medical\libraries\Database;
use Asus\Medical\libraries\Controller;
use Asus\Medical\services\PatientService;
class Pages extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

   public function index()
{
    $doctors = $this->db->readAll('doctor_view');
    $data['doctors'] = array_slice($doctors, 0, 4); // first 6 doctors
    $this->view('pages/home', $data);
}

    public function home()
{
    $doctors = $this->db->readAll('doctor_view'); // store result
    $data['doctors'] = array_slice($doctors, 0, 4); // first 6 doctors
    $this->view('pages/home', $data);
}

    // public function doctors()
    // {
    //     $this->view('pages/doctors');
    // }
     public function appointment()
    {
        $this->view('pages/appointment');
    }

    public function doctorprofile($id) 
    {
    
        $this->view('pages/doctorprofile');
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
