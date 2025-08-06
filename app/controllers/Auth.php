<?php
//for user login and logout

class Auth extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('UserModel');
        $this->db = new Database();
    }

    public function formRegister()
    {
        if (
            $_SERVER['REQUEST_METHOD'] == 'POST' &&
            isset($_POST['email_check']) &&
            $_POST['email_check'] == 1
        ) {
            $email = $_POST['email'];
            // call columnFilter Method from Database.php
            $isUserExist = $this->db->columnFilter('users', 'email', $email);
            if ($isUserExist) {
                echo 'Sorry! email has already taken. Please try another.';
            }
        }
    }

   public function register()
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = $_POST['email'];
        $phone = $_POST['phone'];

        if ($this->db->columnFilter('users', 'email', $email)) {
            setMessage('error', 'This email is already registered!');
            redirect('pages/register');
            return;
        }

        $validation = new UserValidator($_POST);
        $errors = $validation->validateForm();

        if (count($errors) > 0) {
            $this->view('pages/register', $errors);
            return;
        }

        if ($this->db->columnFilter('users', 'phone', $phone)) {
            setMessage('error', 'Phone Number is already exit');
            redirect('pages/register');
            return;
        }

        $name = $_POST['name'];
        $gender = $_POST['gender'];
        $password = base64_encode($_POST['password']); // Note: base64 is NOT secure for real passwords
        $profile_image = 'default_profile.jpg';

        $user = new UserModel();
        $user->name=$name;
        $user->email=$email;
        $user->gender=$gender;
        $user->phone=$phone;
        $user->password=$password;
        $user->profile_image=$profile_image;
        $user->is_login=0;
        $user->is_active=0;
        $user->is_confirmed=0;
        $user->type_id=3;
        $user->status_id=6;

        $userCreated = $this->db->create('users', $user->toArray());

        if ($userCreated) {
            $mail = new Mail();
            $mail->verifyMail($email, $name);
            setMessage('success', 'Please check your Mail box!');
            redirect('pages/login');
        } else {
            // setMessage('error', 'Something went wrong while creating your account.');
            // redirect('pages/register');
        }
    } else {
        // SHOW THE REGISTRATION FORM FOR GET REQUEST
        $this->view('pages/register');
    }
}



public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['email']) && isset($_POST['password'])) {
         
            $email = $_POST['email'];
            $password = base64_encode($_POST['password']);  // Note: consider more secure hashing in production
            
            $user = $this->db->columnFilter('users', 'email', $email);
            
            if (!$user) {
                // User not found
                setMessage('error', 'Invalid email or password.');
                redirect('pages/login');
                return;
            }

            if ($password !== $user['password']) {
                // Password incorrect
                setMessage('error', 'Invalid email or password.');
                redirect('pages/login');
                return;
            }

            // Credentials are valid, set session and login
              $_SESSION['current_user'] =$user;
            $this->db->setLogin($user['id']);

            // Redirect by role
            switch ($user['type_id']) {
                case ROLE_ADMIN:
                    redirect('admin/dashboard');
                    break;

                case ROLE_DOCTOR:
                    $doctor = $this->db->columnFilter('doctor_view', 'user_id', $user['id']);
                    if (!$doctor) {
                        setMessage('error', 'Doctor profile not found.');
                        redirect('pages/login');
                        exit;
                    }
                    $_SESSION['current_doctor'] = $doctor;
                    redirect('doctor/dash');
                    break;

                case ROLE_PATIENT:
                
                    redirect('patient/doctors');
                    break;

                default:
                    setMessage('error', 'Invalid user role.');
                    redirect('pages/login');
                    break;
            }
        } else {
            setMessage('error', 'Please fill in both email and password.');
            redirect('pages/login');
        }
    } else {
        // If GET request, show login form
        $this->view('pages/login');
    }
}

    // function logout($id)
    // {
    //     $this->db->unsetLogin($id);
    //     redirect('pages/login');
    // }
   public function logout($id = null)
{
    // If $id is null, use session-based user ID
    if ($id === null && isset($_SESSION['user_id'])) {
        $id = $_SESSION['user_id'];
    }

    if ($id) {
        $this->db->unsetLogin($id);
    }

    session_unset();
    session_destroy();
    redirect('pages/login');
}
}
