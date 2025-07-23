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
        $isUserExist = $this->db->columnFilter('users', 'email', $email);

        if ($isUserExist) {
            setMessage('error', 'This email is already registered!');
            redirect('pages/register');
        } else {
            $validation = new UserValidator($_POST);
            $data = $validation->validateForm();

            if (count($data) > 0) {
                $this->view('pages/register', $data);
            } else {
            
                $phone = $_POST['phone'];

                $phonecheck =$this->db->columnFilter('users','phone',$phone);

                if($phonecheck){
                    setMessage('error','Phone Number is already exit');
                    redirect('pages/register');
                }
                else{
                $name = $_POST['name'];
                $email = $_POST['email'];
                $gender = $_POST['gender'];
                $password = $_POST['password'];

                $profile_image = 'default_profile.jpg';
                $password = base64_encode($password); // Note: base64 is NOT secure for real passwords

                $user = new UserModel();
                $user->setName($name);
                $user->setEmail($email);
                $user->setGender($gender);
                $user->setPhone($phone);
                $user->setPassword($password);
                $user->setProfileImage($profile_image);
                $user->setIsLogin(0);
                $user->setIsActive(0);
                $user->setIsConfirmed(0);
                $user->setTypeId(3);
                $user->setStatusId(2);
                // $user->setDate(date('Y-m-d H:i:s'));

                $userCreated = $this->db->create('users', $user->toArray());
                // echo "Generated Token: $token<br>";
                // exit();


                    if ($userCreated) {
                    $mail = new Mail();
                    $mail->verifyMail($email, $name);

                    setMessage('success', 'Please check your Mail box!');
                    redirect('pages/login');
                
                } else {
                    // setMessage('error', 'Something went wrong while creating your account.');
                    // redirect('pages/register');
                }
            }
            }
        }
    } else {
        // SHOW THE REGISTRATION FORM FOR GET REQUEST
        $this->view('pages/register');
    }
    }


public function login(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            if(isset($_POST['email']) && isset($_POST['password'])){
                $email = $_POST['email'];
                $password = $_POST['password'];
                $password = base64_encode($password);

                $islogin = $this->db->columnFilter('users','email',$email);
                $currentpassword = $islogin['password'];
                $_SESSION['current_user'] = $islogin;


                if($islogin && $password === $currentpassword){

                    $this->db->setLogin($islogin['id']);


                    if ($islogin) {
                        
                        switch ($islogin['type_id']) {
                            case ROLE_ADMIN:
                                redirect('admin/dashboard');
                                break;

                            case ROLE_DOCTOR:
                                redirect('doctor/newappointment');
                                break;
                            case ROLE_PATIENT:
                                redirect('pages/home');
                                break;

                            default:
                                // Optional: handle unknown roles
                                setMessage('error','Invalid Username & Password');
                                redirect('pages/login');
                                break;
                        }
                    }
                }
            }
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
