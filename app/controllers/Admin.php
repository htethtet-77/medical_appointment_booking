<?php

class Admin extends Controller
{

    private $db;
    public function __construct()
    {
         if (!isset($_SESSION['current_user']) || $_SESSION['current_user']['type_id'] != ROLE_ADMIN) {
            setMessage('error', 'Access denied. Admins only!');
            redirect('pages/login');
            exit;
        }
        $this->model("DoctorModel");
        $this->model("UserModel"); 
        $this->model("TimeslotModel");
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
            // $day = $_POST['availability'];
            // $date = date('Y-m-d'); // Or $_POST['date'] if you're allowing custom dates

            $start_time =  $_POST['start_time'] . ':00';
            $end_time =  $_POST['end_time'] . ':00';
            

            // $photo = $_POST['photo'];

            $encodedPassword = base64_encode($password);
            $imagePath = null;

            if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
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
                    setMessage('error', ' Failed to move uploaded file.');
                    return;
                }
            } else {
                setMessage('error', ' Image not uploaded or error occurred.');
                return;
            }

            // Create user
            $user = new UserModel();
            $user->name=$name;
            $user->email=$email;
            $user->phone=$phone;
            $user->gender=$gender;
            $user->password=$encodedPassword;
            $user->profile_image=$imagePath;
            $user->is_login=0;
            $user->is_active=0;
            $user->is_confirmed=0;
            $user->type_id=2; // 2 = doctor
            $user->status_id=6;

            $user_id = $this->db->create('users', $user->toArray());

            if (!$user_id) {
                setMessage('error', 'Failed to create user.');
                redirect('admin/adddoctor');
                return;
            }

            // Create doctor profile
            $doctor = new DoctorModel();
            // $doctor=setId($id);
            $doctor->degree=$degree;
            $doctor->experience=$experience;
            $doctor->bio=$bio;
            $doctor->fee=$fee;
            $doctor->specialty=$specialty;
            $doctor->address=$address;
            $doctor->user_id=$user_id;

            $doctor_id = $this->db->create('doctorprofile', $doctor->toArray());
    // var_dump($doctorCreated);
    // exit;
            if (!$doctor_id) {
            setMessage('error', 'Failed to create doctor.');
            redirect('admin/adddoctor');
                return;
            } 

            if (strtotime($start_time) >= strtotime($end_time)) {
                setMessage('error', 'Start time must be before end time.');
                redirect('admin/adddoctor');
                return;
            }
            //Create Doctor Timeslot
            $timeslot=new TimeslotModel();
            $timeslot->start_time=$start_time;
            $timeslot->end_time=$end_time;
            $timeslot->user_id=$user_id;
            $timeslot_id = $this->db->create('timeslots', $timeslot->toArray());

            if (!$timeslot_id) {
                setMessage('error', 'Failed to create timeslot.');
                redirect('admin/adddoctor');
                return;
            }

            
            setMessage('success', 'Doctor added successfully!');
            redirect('admin/doctorlist');

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
        public function updatedoctor() {
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
                // $day = $_POST['availability'];
                $start_time = $_POST['start_time'] . ':00';
                $end_time = $_POST['end_time'] . ':00';

                $existingUser = $this->db->getById('users', $id);
                if (!$existingUser) {
                    setMessage('error', 'User not found.');
                    redirect('admin/doctorlist');
                    return;
                }

                // Use old image unless a new one is uploaded
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
                        redirect('admin/editdoctor/'.$id);
                        return;
                    }
                }

                // Only encode password if new one entered
                $finalPassword = !empty($password) ? base64_encode($password) : $existingUser['password'];

                $user = new UserModel();
                $user->name=$name;
                $user->email=$email;
                $user->phone=$phone;
                $user->gender=$gender;
                $user->password=$finalPassword;
                $user->profile_image=$imagePath;
                $user->is_login=0;
                $user->is_active=1;
                $user->is_confirmed=1;
                $user->type_id=2;
                $user->status_id=6;

                $this->db->update('users', $id, $user->toArray());

                $doctor = new DoctorModel();
                $doctor->degree=$degree;
                $doctor->experience=$experience;
                $doctor->bio=$bio;
                $doctor->fee=$fee;
                $doctor->specialty=$specialty;
                $doctor->address=$address;
                $doctor->user_id=$id;

                $existingDoctor = $this->db->columnFilter('doctorprofile', 'user_id', $id);
                if ($existingDoctor && isset($existingDoctor['id'])) {
                    $this->db->update('doctorprofile', $existingDoctor['id'], $doctor->toArray());
                } else {
                    $this->db->create('doctorprofile', $doctor->toArray());
                }

                $timeslot = new TimeslotModel();
                $timeslot->start_time=$start_time;
                $timeslot->end_time=$end_time;
                $timeslot->user_id=$id;

                $timeslotRow = $this->db->columnFilter('timeslots', 'user_id', $id);
                if ($timeslotRow && isset($timeslotRow['id'])) {
                    $this->db->update('timeslots', $timeslotRow['id'], $timeslot->toArray());
                } else {
                    $this->db->create('timeslots', $timeslot->toArray());
                }

                setMessage('success', 'Doctor updated successfully!');
                redirect('admin/doctorlist');
            } else {
                setMessage('error', 'Invalid request.');
                redirect('admin/doctorlist');
            }
    }


    

 
        public function patientlist()
        {
            $patient=$this->db->readAll("users");
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

