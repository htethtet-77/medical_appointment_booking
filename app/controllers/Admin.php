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

        // Check if email exists in users table (covers patients and doctors)
        $isUserExist = $this->db->columnFilter('users', 'email', $email);
        if ($isUserExist) {
            setMessage('error', 'This email is already registered!');
            redirect('admin/adddoctor');
            return;  // stop execution after redirect
        }

        // Validate form data
        $validation = new UserValidator($_POST);
        $data = $validation->validateForm();
        if (count($data) > 0) {
            $this->view('admin/adddoctor', $data);
            return;
        }

        $phone = $_POST['phone'];

        // Check if phone number exists
        $phonecheck = $this->db->columnFilter('users', 'phone', $phone);
        if ($phonecheck) {
            setMessage('error', 'Phone number already exists!');
            redirect('admin/adddoctor');
            return;  // stop execution after redirect
        }

        // Collect form data
        $name = $_POST['name'];
        $password = $_POST['password'];
        $gender = $_POST['gender'];
        $degree = $_POST['degree'];
        $experience = (int) $_POST['experience']; // ensure integer
        $bio = $_POST['bio'];
        $fee = $_POST['fee'];
        $specialty = $_POST['specialty'];
        $address = $_POST['address'];
        $availability = $_POST['availability'];
        

        // $photo = $_POST['photo'];

        $encodedPassword = base64_encode($password);
        $imagePath = null;

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/image/';
            $originalName = basename($_FILES['image']['name']);
            $imageName = uniqid('book_') . '_' . $originalName;
            $targetPath = $uploadDir . $imageName;


            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            
            if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                $imagePath = $targetPath;
            } else {
                setMessage('error', ' Failed to move uploaded file.');
                return;
            }
        } else {
            setMessage('error', ' Image not uploaded or error occurred.');
            return;
        }

        // Create user
        $user = new UserModel();
        $user->setName($name);
        $user->setEmail($email);
        $user->setPhone($phone);
        $user->setGender($gender);
        $user->setPassword($encodedPassword);
        $user->setProfileImage($imagePath);
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
        // $doctor=setId($id);
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
        $this->view('admin/adddoctor');
    }
}


    public function doctorlist()
    {
        $doctorWithUserInfo = $this->db->readAll('doctor_view');
        $data = [
            'doctors' => $doctorWithUserInfo
        ];

        $this->view('admin/doctorlist', $data);
    }

  public function deletedoctor()
{
    $user_id = $_POST['user_id'];

    if (!$user_id) {
        setMessage('error', 'User ID missing.');
        redirect('admin/doctorlist');
        return;
    }

    // 1. Find doctorprofile record by user_id (foreign key)
    $doctorProfile = $this->db->columnFilter('doctorprofile', 'user_id', $user_id);

    if ($doctorProfile && isset($doctorProfile['id'])) {
        // 2. Delete doctorprofile by id
        $this->db->delete('doctorprofile', $doctorProfile['id']);
    }

    // 3. Delete user by id (user_id)
    $this->db->delete('users', $user_id);

    setMessage('success', 'Doctor successfully deleted.');
    redirect('admin/doctorlist');
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
