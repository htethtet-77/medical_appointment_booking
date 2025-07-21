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
        $this->view('pages/home');
    }
    public function doctors()
    {
        $this->view('pages/doctors');
    }
     public function appointment()
    {
        $this->view('pages/appointment');
    }

    public function doctorprofile()
    {
    
        $this->view('pages/doctorprofile');
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

    // public function doctorprofile($id) {
    //     $doctorData = $this->db->selectWhere('doctors', 'id', $id);

    //     if ($doctorData) {
    //         $doctor = new Doctor();
    //         $doctor->setId($doctorData[0]->id);
    //         $doctor->setName($doctorData[0]->name);
    //         $doctor->setSpecialty($doctorData[0]->specialty);
    //         $doctor->setExperience($doctorData[0]->experience);
    //         $doctor->setPhone($doctorData[0]->phone);
    //         $doctor->setImage($doctorData[0]->image);
    //         $doctor->setDescription($doctorData[0]->description);

    //         $this->view('pages/doctorprofile', ['doctor' => $doctor]);
    //     } else {
    //         redirect('pages/doctors');
    //     }
    // }



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
