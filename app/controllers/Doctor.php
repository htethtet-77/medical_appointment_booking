<?php

class Doctor extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('DoctorModel');
        $this->db = new Database();
    }

     public function newappointment()
    {
        $this->view('doctor/newappointment');
    }
     public function dash()
    {
        $this->view('doctor/dash');
    }
    public function all()
    {
        $this->view('doctor/all');
    }
    public function profile()
    {

        $this->view('doctor/profile');
    }

    // Show all doctors
//     public function index()
//     {
//         $doctors = $this->db->getAll('doctors');
//         $this->view('pages/doctors/index', ['doctors' => $doctors]);
//     }

//     // Show doctor profile by ID
//     public function profile($id)
//     {
//         $doctor = $this->db->getById('doctors', $id);

//         if ($doctor) {
//             $this->view('pages/doctorprofile', ['doctor' => $doctor]);
//         } else {
//             redirect('doctor/index');
//         }
//     }

//     // Add new doctor form
//     public function create()
//     {
//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $data = [
//                 'name'        => $_POST['name'],
//                 'specialty'   => $_POST['specialty'],
//                 'qualification' => $_POST['qualification'],
//                 'experience'  => $_POST['experience'],
//                 'email'       => $_POST['email'],
//                 'phone'       => $_POST['phone'],
//                 'image'       => $_POST['image'] ?? '',
//                 'description' => $_POST['description']
//             ];

//             if ($this->db->insert('doctors', $data)) {
//                 redirect('doctor/index');
//             } else {
//                 $this->view('pages/doctors/create', ['error' => 'Failed to add doctor']);
//             }
//         } else {
//             $this->view('pages/doctors/create');
//         }
//     }

//     // Edit doctor form
//     public function edit($id)
//     {
//         $doctor = $this->db->getById('doctors', $id);
//         if (!$doctor) {
//             redirect('doctor/index');
//         }

//         if ($_SERVER['REQUEST_METHOD'] == 'POST') {
//             $data = [
//                 'id'          => $id,
//                 'name'        => $_POST['name'],
//                 'specialty'   => $_POST['specialty'],
//                 'qualification' => $_POST['qualification'],
//                 'experience'  => $_POST['experience'],
//                 'email'       => $_POST['email'],
//                 'phone'       => $_POST['phone'],
//                 'image'       => $_POST['image'] ?? '',
//                 'description' => $_POST['description']
//             ];

//             if ($this->db->updateById('doctors', $id, $data)) {
//                 redirect('doctor/index');
//             } else {
//                 $this->view('pages/doctors/edit', ['error' => 'Failed to update doctor', 'doctor' => $doctor]);
//             }
//         } else {
//             $this->view('pages/doctors/edit', ['doctor' => $doctor]);
//         }
//     }

//     // Delete doctor
//     public function delete($id)
//     {
//         if ($this->db->deleteById('doctors', $id)) {
//             redirect('doctor/index');
//         } else {
//             die('Delete failed.');
//         }
//     }
}
