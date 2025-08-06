<?php
require_once APPROOT . '/middleware/authMiddleware.php';
class Admin extends Controller
{

    private $db;
    public function __construct()
    {
        AuthMiddleware::adminOnly();
        $this->model("DoctorModel");
        $this->model("UserModel"); 
        $this->model("TimeslotModel");
        $this->db = new Database();
    }

    public function index()
    {
        $this->view('admin/dashboard');
    }

   public function adddoctor() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return $this->view('admin/adddoctor');
        }

        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        // Check duplicates
        if ($this->db->columnFilter('users', 'email', $email)) {
            setMessage('error', 'This email is already registered!');
            return redirect('admin/adddoctor');
        }
        if ($this->db->columnFilter('users', 'phone', $phone)) {
            setMessage('error', 'Phone number already exists!');
            return redirect('admin/adddoctor');
        }

        // Validate form
        $validation = new UserValidator($_POST);
        $errors = $validation->validateForm();
        if (!empty($errors)) {
            return $this->view('admin/adddoctor', $errors);
        }

        // Collect form data
        $name = $_POST['name'] ?? '';
        $password = $_POST['password'] ?? '';
        $gender = $_POST['gender'] ?? '';
        $degree = $_POST['degree'] ?? '';
        $experience = (int)($_POST['experience'] ?? 0);
        $bio = $_POST['bio'] ?? '';
        $fee = $_POST['fee'] ?? '';
        $specialty = $_POST['specialty'] ?? '';
        $address = $_POST['address'] ?? '';
        $start_time = ($_POST['start_time'] ?? '') . ':00';
        $end_time = ($_POST['end_time'] ?? '') . ':00';

        if (strtotime($start_time) >= strtotime($end_time)) {
            setMessage('error', 'Start time must be before end time.');
            return redirect('admin/adddoctor');
        }

        // Handle image upload
        $imagePath = null;
        if (!empty($_FILES['image']['tmp_name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = 'public/image/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $imageName = uniqid('doctor_') . '_' . basename($_FILES['image']['name']);
            $targetPath = $uploadDir . $imageName;

            if (!move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                setMessage('error', 'Failed to move uploaded file.');
                return redirect('admin/adddoctor');
            }
            $imagePath = $targetPath;
        } else {
            setMessage('error', 'Image not uploaded or error occurred.');
            return redirect('admin/adddoctor');
        }

        // Encode password (better to use password_hash() for real apps)
        $encodedPassword = base64_encode($password);

        // Call stored procedure
        $user_id = $this->db->callProcedure('add_doctor', [
            $name,
            $email,
            $phone,
            $gender,
            $encodedPassword,
            $imagePath,
            $degree,
            $experience,
            $bio,
            $fee,
            $specialty,
            $address,
            $start_time,
            $end_time
        ]);

        if (!$user_id) {
            setMessage('error', 'Failed to add doctor.');
            redirect('admin/adddoctor');
            return;
        }
        

        setMessage('success', 'Doctor added successfully!');
        redirect('admin/doctorlist');

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
            $user_id = $_POST['user_id'] ?? null;

            if (!$user_id) {
                setMessage('error', 'User ID missing.');
                redirect('admin/doctorlist');
                return;
            }

            // Delete timeslots
            $timeslots = $this->db->columnFilter('timeslots', 'user_id', $user_id);
            if (!empty($timeslots)) {
                $this->db->delete('timeslots', $timeslots['id']);    

                // Delete appointment(s)
                $appointment = $this->db->columnFilter('appointment','timeslot_id',$timeslots['id']);
                if ($appointment && isset($appointment['id'])) {
                    $this->db->delete('appointment', $appointment['id']);
                }
            }

            // Always delete doctorprofile
            $doctorProfile = $this->db->columnFilter('doctorprofile', 'user_id', $user_id);
            if ($doctorProfile && isset($doctorProfile['id'])) {
                $this->db->delete('doctorprofile', $doctorProfile['id']);
            }

            // Delete user
            $delete = $this->db->delete('users', $user_id);

            if ($delete) {
                setMessage('success', 'Doctor deleted successfully!');
            } else {
                setMessage('error', 'Failed to delete doctor.');
            }

            redirect('admin/doctorlist');
        }


        public function editdoctor($user_id ){
            if (!$user_id) {
                setMessage('error', 'No doctor ID provided.');
                redirect('admin/doctorlist');
                return;
            }
            $user = $this->db->getById('users', $user_id); // <- user only
            $doctor = $this->db->columnFilter('doctorprofile', 'user_id', $user_id); // <- for doctor
            $timeslot = $this->db->columnFilter('timeslots', 'user_id', $user_id); // <- for timeslot

            $data=[
                'users' =>$user,
                'doctorprofile' =>$doctor,
                'timeslots'=>$timeslot
            ];
            $this->view('admin/editdoctor',$data);
        }
        
        public function updatedoctor() 
        {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $id = $_POST['id'];
                $name = $_POST['name'];
                $password = trim($_POST['password']);
                $email = $_POST['email'];
                $phone = $_POST['phone'];
                $gender = $_POST['gender'];
                $degree = $_POST['degree'];
                $experience = $_POST['experience'];
                $bio = $_POST['bio'];
                $fee = $_POST['fee'];
                $specialty = $_POST['specialty'];
                $address = $_POST['address'];
                $start_time = $_POST['start_time'] . ':00';
                $end_time = $_POST['end_time'] . ':00';

                $existingUser = $this->db->getById('users', $id);
                if (!$existingUser) {
                    setMessage('error', 'User not found.');
                    redirect('admin/doctorlist');
                    return;
                }

                // Default to existing image
                $imagePath = $existingUser['profile_image'];

                if (!empty($_FILES['image']['name'])) {
                    $uploadDir = 'public/image/';
                    $originalName = basename($_FILES['image']['name']);
                    $imageName = uniqid('doctor_') . '_' . $originalName;
                    $targetPath = $uploadDir . $imageName;

                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath)) {
                        $imagePath = $targetPath;
                    } else {
                        setMessage('error', 'Image upload failed.');
                        redirect('admin/editdoctor/' . $id);
                        return;
                    }
                }

                // Encode password only if new one is entered
                $finalPassword = !empty($password) ? base64_encode($password) : $existingUser['password'];

                try {
                    // Call the stored procedure to update everything at once
                    $this->db->callProcedure('update_doctor', [
                        $id,
                        $name,
                        $email,
                        $phone,
                        $gender,
                        $finalPassword,
                        $imagePath,
                        $degree,
                        $experience,
                        $bio,
                        $fee,
                        $specialty,
                        $address,
                        $start_time,
                        $end_time
                    ]);

                    setMessage('success', 'Doctor updated successfully!');
                } catch (Exception $e) {
                    setMessage('error', 'Failed to update doctor: ' . $e->getMessage());
                }

                redirect('admin/doctorlist');
            } else {
                setMessage('error', 'Invalid request.');
                redirect('admin/doctorlist');
            }
        }

    

 
        public function patientlist()
        {
            $patient=$this->db->columnFilterAll('users','type_id',3);
            $data=[
                'user'=>$patient
            ];
            $this->view('admin/patientlist',$data);
        }
        public function appointmentview()
        {
            $appointments = $this->db->readAll('appointment_view');

            $data = [
                'appointments' => $appointments,
                'todayDate'    => date('Y-m-d')
            ];

            $this->view('admin/appointmentview', $data);
        }

        public function dashboard()
        {
            $appointments = $this->db->readAll('appointment_view');
            
            $appointmentsByDate = [];
            $totalAppointments = 0;
            $todaysAppointments = 0;
            $dateString = date('Y-m-d'); // today

            if (!empty($appointments)) {
                foreach ($appointments as $appointment) {
                    $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date'] ));

                    if ($appointmentDate === $dateString) {
                        if (!isset($appointmentsByDate[$appointmentDate])) {
                            $appointmentsByDate[$appointmentDate] = [];
                        }
                        $appointmentsByDate[$appointmentDate][] = $appointment;
                        $todaysAppointments++;
                    }

                    $totalAppointments++;
                }
            }

            // Count unique patients
            $totalPatients = count(array_unique(array: array_column($appointments, 'patient_id')));

            $data = [
                'appointmentsByDate' => $appointmentsByDate,
                'totalAppointments'  => $totalAppointments,
                'todaysAppointments' => $todaysAppointments,
                'totalPatients'      => $totalPatients,
                'todayDate'          => $dateString
            ];

            $this->view('admin/dashboard', $data);
        }



}

