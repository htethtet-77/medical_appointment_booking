<?php

class Admin extends Controller
{

    private $db;
    public function __construct()
    {
        $this->model("DoctorModel");
        $this->model("UserModel"); 
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('admin/dashboard');
    }

public function adddoctor()
{
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $isUserExist = $this->db->columnFilter('users', 'email', $email);

        if ($isUserExist) {
            setMessage('error', 'This email is already registered!');
            redirect('admin/adddoctor');
        }

        $validation = new UserValidator($_POST);
        $data = $validation->validateForm();

        if (count($data) > 0) {
            $this->view('admin/adddoctor', $data);
            return;
        }

        $phone = $_POST['phone'];
        $phonecheck = $this->db->columnFilter('users', 'phone', $phone);

        if ($phonecheck) {
            setMessage('error', 'Phone number already exists!');
            redirect('admin/adddoctor');
            return;
        }


        // Collect form data
        $name = $_POST['name'];
        $password = $_POST['password'];
        $gender = $_POST['gender'] ?? null;
        $degree = $_POST['degree'];
        $experience = (int) $_POST['experience']; // Make sure it's an integer
        $bio = $_POST['bio'];
        $fee = $_POST['fee'];
        $specialty = $_POST['specialty'];
        $address = $_POST['address'];
        $availability = $_POST['availability'];

        $encodedPassword = base64_encode($password);
        $profile_image = 'default_profile.jpg';

        // Create user
        $user = new UserModel();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setGender($gender); // Now accepts NULL if not set
        $user->setPassword($encodedPassword);
        $user->setProfileImage($profile_image);
        $user->setIsLogin(0);
        $user->setIsActive(0);
        $user->setIsConfirmed(0);
        $user->setTypeId(2); // 2 = doctor
        $user->setStatusId(2);

        $user_id = $this->db->create('users', $user->toArray());

        if (!$user_id) {
            setMessage('error', 'Failed to create user.');
            redirect('admin/adddoctor');
            return;
        }

        // Create doctor profile
        $doctor = new DoctorModel();
        $doctor->setDegree($degree);
        $doctor->setExperience($experience);
        $doctor->setBio($bio);
        $doctor->setFee($fee);
        $doctor->setSpecialty($specialty);
        $doctor->setAddress($address);
        $doctor->setAvailability($availability);
        $doctor->setUserId($user_id);

        $doctorCreated = $this->db->create('doctorprofile', $doctor->toArray());

        if ($doctorCreated) {
            setMessage('success', 'Doctor successfully added!');
            redirect('admin/doctorlist');
        } else {
            setMessage('error', 'Failed to create doctor profile.');
            redirect('admin/adddoctor');
        }
    } else {
        // GET Request – Show the form
        $this->view('admin/adddoctor');
    }
}

  public function doctorlist()
{
    $doctors = $this->db->readAll('doctorprofile');
    $users = $this->db->readAll('users');

    // Build a map of user_id to user info
    $users_map = [];
    foreach ($users as $user) {
        $users_map[$user['id']] = $user;
    }

    // Merge each doctor with their user info
    $doctorWithUserInfo = [];
    foreach ($doctors as $doctor) {
        $user_id = $doctor['user_id'];
        if (isset($users_map[$user_id])) {
            $doctorWithUserInfo[] = array_merge($doctor, $users_map[$user_id]);
        } else {
            $doctorWithUserInfo[] = $doctor; // fallback if no user found
        }
    }

    $data = [
        'doctors' => $doctorWithUserInfo
    ];

    $this->view('admin/doctorlist', $data);
}




    // public function adddoctor()
    // {
        
    //     $this->view('admin/adddoctor');
    // }
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

    // public function doctorList() {
    //     $doctors = $this->db->readWithCondition('users', 'role', 'doctor');
    //     $this->view('admin/doctorlist', ['doctors' => $doctors]);
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
