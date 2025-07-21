<?php

class Admin extends Controller
{

    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('admin/dashboard');
    }
     public function doctorlist()
    {
        $this->view('admin/doctorlist');
    }
    public function adddoctor()
    {
        $this->view('admin/adddoctor');
    }
     public function patientlist()
    {
        $this->view('admin/patientlist');
    }
    public function appointmentview()
    {
        $this->view('admin/appointmentview');
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


    public function dashboard()
    {
        $income = $this->db->incomeTransition();
        $expense = $this->db->expenseTransition();

        $data = [
            'income' => isset($income['amount']) ? $income : ['amount' => 0],
            'expense' => isset($expense['amount']) ? $expense : ['amount' => 0]
        ];

        $this->view('admin/dashboard', $data);
    }

}
