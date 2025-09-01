<?php
//for user login and logout
namespace Asus\Medical\controllers;
use Asus\Medical\libraries\Controller;
use Asus\Medical\libraries\Database;
use Asus\Medical\helpers\UserValidator;
use Asus\Medical\libraries\Mail;
use Asus\Medical\models\UserModel;
use function Asus\Medical\helpers\setMessage;
// require_once __DIR__ . '/../OpenIDConnect/OpenIDConnectClient.php';
require_once APPROOT . '/../OpenIDConnect/OpenIDConnectClient.php';
use Jumbojett\OpenIDConnectClient;
use Asus\Medical\helpers\OIDCUserHelper;

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

        // -----------------------------
        // RECAPTCHA V3 VERIFICATION
        // -----------------------------
        $recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';
        $secret = RECAPTCHA_V3_SECRET;
        $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret={$secret}&response={$recaptchaResponse}");
        $captcha = json_decode($verify);

        if (!$captcha->success || $captcha->score < 0.5) {
            setMessage('error', 'CAPTCHA verification failed. Are you a bot?');
            return redirect('pages/register');
        }

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



/*public function login() {
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
                $patient = $this->db->columnFilter('users', 'id', $user['id']);
                    if (!$patient) {
                        setMessage('error', 'Doctor profile not found.');
                        redirect('pages/login');
                        exit;
                    }
                    $_SESSION['current_patient'] = $patient;
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
}*/
public function oauthLogin()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    $oidc = new OpenIDConnectClient(
        OIDC_PROVIDER_URL,
        OIDC_CLIENT_ID,
        OIDC_CLIENT_SECRET
    );

    $oidc->setRedirectURL(OIDC_REDIRECT_URI);
    $oidc->addScope(['openid','email','profile']);

    // Required for PKCE
    $oidc->setCodeChallengeMethod('S256');

    $oidc->authenticate(); // redirects user to Google
}



public function oauthCallback()
{
    // Start session if not already started
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    try {
        $oidc = new OpenIDConnectClient(
            OIDC_PROVIDER_URL,
            OIDC_CLIENT_ID,
            OIDC_CLIENT_SECRET
        );

        // Set redirect URL and scopes
        $oidc->setRedirectURL(OIDC_REDIRECT_URI);
        $oidc->addScope(['openid', 'email', 'profile']);

        // Use PKCE for security
        $oidc->setCodeChallengeMethod('S256');

        // Authenticate: this reads $_GET['code'] automatically
        $oidc->authenticate();

        // Get verified claims from Google
        $claims = $oidc->getVerifiedClaims();

        // Use helper to map Google user to local database
        $helper = new OIDCUserHelper($this->db);
        $userId = $helper->handleOIDCLogin('google', (array)$claims);

        if (!$userId) {
            setMessage('error', 'Unable to process login. Please try again.');
            redirect('pages/login');
            return;
        }

        // Regenerate session for security
        session_regenerate_id(true);

        // Fetch the full user info from DB
        $_SESSION['current_user'] = $this->db->getById('users', $userId);

        // Mark the user as logged in
        $this->db->setLogin($userId);

        // Redirect based on role
        switch ($_SESSION['current_user']['type_id']) {
            case ROLE_ADMIN:
                redirect('admin/dashboard');
                break;
            case ROLE_DOCTOR:
                $doctor = $this->db->columnFilter('doctor_view', 'user_id', $userId);
                $_SESSION['current_doctor'] = $doctor;
                redirect('doctor/dash');
                break;
            case ROLE_PATIENT:
                $patient = $this->db->columnFilter('users', 'id', $userId);
                $_SESSION['current_patient'] = $patient;
                redirect('patient/doctors');
                break;
            default:
                setMessage('error', 'Invalid user role.');
                redirect('pages/login');
                break;
        }

    } catch (\Jumbojett\OpenIDConnectClientException $e) {
        // Catch errors from the library
        setMessage('error', 'OIDC login failed: ' . $e->getMessage());
        redirect('pages/login');
    } catch (\Exception $e) {
        setMessage('error', 'Unexpected error: ' . $e->getMessage());
        redirect('pages/login');
    }
}


public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = trim($_POST['email'] ?? '');
        $password = trim($_POST['password'] ?? '');

        if (!$email || !$password) {
            setMessage('error', 'Please fill in both email and password.');
            redirect('pages/login');
            return;
        }
      
            $recaptcha_response = $_POST['g-recaptcha-response'] ?? '';
            $secret = RECAPTCHA_V2_SECRET; // Correct
            $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret&response=$recaptcha_response");
            $captcha_success = json_decode($verify);

            if (!$captcha_success->success) {
                setMessage('error', 'Please complete CAPTCHA.');
                redirect('pages/login');
                return;
            }
        

        // Encode password (your current method)
        $encoded_password = base64_encode($password);

        // Check user credentials
        $user = $this->db->loginCheck($email, $encoded_password);

        if (!$user) {
            setMessage('error', 'Invalid email or password.');
            redirect('pages/login');
            return;
        }

        // Set session and update login status
        $_SESSION['current_user'] = $user;
        $this->db->setLogin($user['id']);

        // Redirect based on role
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
                $patient = $this->db->columnFilter('users', 'id', $user['id']);
                if (!$patient) {
                    setMessage('error', 'Patient profile not found.');
                    redirect('pages/login');
                    exit;
                }
                $_SESSION['current_patient'] = $patient;
                redirect('patient/doctors');
                break;
            default:
                setMessage('error', 'Invalid user role.');
                redirect('pages/login');
                break;
        }
    } else {
        // GET request: show login form
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
        // Use session user ID if $id not provided
        if ($id === null && isset($_SESSION['current_user']['id'] )) {
            $id = $_SESSION['current_user']['id'];
        }

        // Update database login status if needed
        if ($id) {
            $this->db->unsetLogin($id);
        }

        // Clear all session data
        $_SESSION = [];

        // Remove session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
    session_unset();
        // Destroy the session
        session_destroy();

        // Redirect to login page
        redirect('pages/login');
    }
 /*  public function logout($id=null)
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
}*/
} 