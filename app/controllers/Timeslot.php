<?php
class Timeslot extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('TimeslotModel');
        $this->db = new Database();
    }

       public function generateTimeSlots($start, $end, $duration = 20)
    {
        $slots = [];
        $current = strtotime($start);
        $endTime = strtotime($end);

        while ($current < $endTime) { 
            $slots[] = date("H:i", $current);
            $current = strtotime("+$duration minutes", $current);
        }
        return $slots;
    }

public function time($doctorId)
{
    $doctor = $this->db->getById('doctorprofile', $doctorId);
    if (!$doctor) {
        die("Doctor not found");
    }

    $date = $_GET['date'] ?? date('Y-m-d');

    // Fetch available timeslot for this doctor and day
    $timeslot = $this->db->columnFilter('timeslots', 'user_id', $doctor['user_id']);

    $start = $timeslot['start_time'] ?? null;
    $end = $timeslot['end_time'] ?? null;

    $timeSlots = [];
    if ($start && $end) {
        $allSlots = $this->generateTimeSlots($start, $end, 20);

        // Fetch booked timeslots from model method
        $bookedSlotsData = $this->db->getBookedTimes($doctorId, $date);
        $bookedSlots = array_map(fn($b) => date("H:i", strtotime($b['appointment_time'])), $bookedSlotsData);

        // Remove booked slots
        $timeSlots = array_diff($allSlots, $bookedSlots);
    }

    $data = [
        'doctor' => $doctor,
        'timeSlots' => $timeSlots,
        'date' => $date
    ];

    $this->view('pages/appointmentform', $data);
}

}