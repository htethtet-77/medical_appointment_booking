<?php
require_once __DIR__ . '/../repositories/AdminRepository.php';
require_once __DIR__ . '/../interfaces/AdminRepositoryInterface.php';
require_once __DIR__ . '/../helpers/UserValidator.php';
require_once __DIR__ . '/../services/ImageUploadService.php';
require_once __DIR__ . '/../interfaces/ImageUploadServiceInterface.php';


class AdminService
{
    protected AdminRepositoryInterface $adminRepo;
    protected UserValidator $validator;
    protected ImageUploadServiceInterface $imageUploader;

    public function __construct(
        AdminRepositoryInterface $adminRepo,
        UserValidator $validator,
        ImageUploadServiceInterface $imageUploader
    ) {
        $this->adminRepo = $adminRepo;
        $this->validator = $validator;
        $this->imageUploader = $imageUploader;
    }

    public function getAllDoctors()
    {
        return $this->adminRepo->getAllDoctors();
    }

    public function addDoctor(array $data, array $file)
    {
        // Check email & phone duplicates
        if ($this->adminRepo->emailExists($data['email'])) {
            throw new Exception('This email is already registered!');
        }
        if ($this->adminRepo->phoneExists($data['phone'])) {
            throw new Exception('Phone number already exists!');
        }

        // Validate form data
        $this->validator = new UserValidator($data);
        $errors = $this->validator->validateForm();
        if (!empty($errors)) {
            throw new Exception(json_encode($errors));
        }

        // Validate working hours
        if (strtotime($data['start_time']) >= strtotime($data['end_time'])) {
            throw new Exception('Start time must be before end time.');
        }

        // Handle image upload
        $uploadDir = dirname(APPROOT) . '/public/image/';
        $imageName = $this->imageUploader->upload($file, $uploadDir, 'doctor_');

        // Store relative path for web access
        $imagePath = 'image/' . $imageName;

        // Encode password (⚠️ Consider hashing instead of base64 for real security)
        $encodedPassword = base64_encode(trim($data['password']));

        $params = [
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['gender'],
            $encodedPassword,
            $imagePath,
            $data['degree'],
            (int)$data['experience'],
            $data['bio'],
            $data['fee'],
            $data['specialty'],
            $data['address'],
            $data['start_time'] . ':00',
            $data['end_time'] . ':00',
        ];

        $userId = $this->adminRepo->addDoctor($params);
        if (!$userId) {
            throw new Exception('Failed to add doctor.');
        }

        return $userId;
    }

    public function deleteDoctor($user_id)
    {
        if (empty($user_id)) {
            throw new Exception('User ID missing.');
        }

        // Delete related timeslots & appointments
        $timeslot = $this->adminRepo->findTimeslotsByUserId($user_id);
        if (!empty($timeslot)) {
            $this->adminRepo->deleteTimeslot($timeslot['id']);

            $appointments = $this->adminRepo->findAppointmentsByTimeslotId($timeslot['id']);
            foreach ($appointments as $appointment) {
                $this->adminRepo->deleteAppointment($appointment['id']);
            }
        }

        // Delete doctor profile
        $doctorProfile = $this->adminRepo->findDoctorProfileByUserId($user_id);
        if ($doctorProfile && isset($doctorProfile['id'])) {
            $this->adminRepo->deleteDoctorProfile($doctorProfile['id']);
        }

        // Delete user record
        $result = $this->adminRepo->deleteUser($user_id);
        if (!$result) {
            throw new Exception('Failed to delete doctor.');
        }

        return true;
    }

    public function getDoctorDetails($user_id)
    {
        $user = $this->adminRepo->findUserById($user_id);
        if (!$user) {
            throw new Exception('Doctor not found.');
        }

        return [
            'users' => $user,
            'doctorprofile' => $this->adminRepo->findDoctorProfileByUserId($user_id),
            'timeslots' => $this->adminRepo->findTimeslotsByUserId($user_id)
        ];
    }

    public function updateDoctor(array $data, array $file)
    {
        $existingUser = $this->adminRepo->findUserById($data['id']);
        if (!$existingUser) {
            throw new Exception('User not found.');
        }

        // Handle image upload only if new file provided
        $imagePath = $existingUser['profile_image'];
        // If new file uploaded
        if (!empty($file['tmp_name']) && $file['error'] === UPLOAD_ERR_OK) {
            $uploadDir = dirname(APPROOT) . '/public/image/';
            $imageName = $this->imageUploader->upload($file, $uploadDir, 'doctor_');
            $imagePath = 'image/' . $imageName;
        }

        // Keep existing password if none provided
        $finalPassword = !empty(trim($data['password']))
            ? base64_encode(trim($data['password']))
            : $existingUser['password'];

        $params = [
            $data['id'],
            $data['name'],
            $data['email'],
            $data['phone'],
            $data['gender'],
            $finalPassword,
            $imagePath,
            $data['degree'],
            (int)$data['experience'],
            $data['bio'],
            $data['fee'],
            $data['specialty'],
            $data['address'],
            $data['start_time'] . ':00',
            $data['end_time'] . ':00',
        ];

        $this->adminRepo->updateDoctor($params);
        return true;
    }

    public function getPatients()
    {
        return $this->adminRepo->getPatients();
    }

    public function getAppointments()
    {
        return $this->adminRepo->getAppointments();
    }

    public function getDashboardData()
    {
        $appointments = $this->adminRepo->getAppointments();
        $appointmentsByDate = [];
        $totalAppointments = count($appointments);
        $todaysAppointments = 0;
        $dateString = date('Y-m-d');

        foreach ($appointments as $appointment) {
            $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date']));
            if ($appointmentDate === $dateString) {
                $appointmentsByDate[$appointmentDate][] = $appointment;
                $todaysAppointments++;
            }
        }

        $totalPatients = count(array_unique(array_column($appointments, 'patient_id')));

        return [
            'appointmentsByDate' => $appointmentsByDate,
            'totalAppointments' => $totalAppointments,
            'todaysAppointments' => $todaysAppointments,
            'totalPatients' => $totalPatients,
            'todayDate' => $dateString
        ];
    }
}
