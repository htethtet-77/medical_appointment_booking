<?php
require_once APPROOT . '/middleware/authMiddleware.php';

class Doctor extends Controller
{
    private $db;

    public function __construct()
    {
        AuthMiddleware::doctorOnly();
        $this->model('DoctorModel');
        $this->db = new Database();
    }

    public function dash()
    {
        $doctor = $_SESSION['current_doctor']['user_id'];
        $date = new DateTime();
        $dateString = $date->format('Y-m-d');
        
        // Get all appointments for this doctor
        $allAppointments = $this->db->columnFilterAll('appointment_view', 'doctor_user_id', $doctor);
        
        // Group appointments by date
        $appointmentsByDate = [];
        $totalAppointments = 0;
        $todaysAppointments = 0;
        
        if (!empty($allAppointments)) {
            foreach ($allAppointments as $appointment) {
                $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date']));
                
                if (!isset($appointmentsByDate[$appointmentDate])) {
                    $appointmentsByDate[$appointmentDate] = [];
                }
                
                $appointmentsByDate[$appointmentDate][] = $appointment;
                $totalAppointments++;
                
                // Count today's appointments
                if ($appointmentDate === $dateString) {
                    $todaysAppointments++;
                }
            }
        }
        $totalPatients = count(array_unique(array: array_column($allAppointments, 'patient_id')));

        // Sort dates in descending order (newest first)
        krsort($appointmentsByDate);
        
        $data = [
            'appointmentsByDate' => $appointmentsByDate,
            'todayDate' => $dateString,
            'totalAppointments' => $totalAppointments,
            'todaysAppointments' => $todaysAppointments,
            'totalPatients' => $totalPatients// You can calculate this from your database
        ];
        
        $this->view('doctor/dash', $data);
    }

    // public function all()
    // {
    //     $doctor = $_SESSION['current_doctor']['user_id'];
    //     $appointments = $this->db->columnFilterAll('appointment_view', 'doctor_user_id', $doctor);
    //     $status = $this->db->getById('status', 2);
    //     $data = [
    //         'appointments' => $appointments,
    //         'status' => $status
    //     ];
    //     $this->view('doctor/all', $data);
    // }
    
    public function profile()
    {
        $this->view('doctor/profile');
    }
}