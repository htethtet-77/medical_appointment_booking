<?php
class Timeslot extends Controller
{
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    // Create timeslots for a doctor
    public function addTimeslot($doctor_id) {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $day = $_POST['day'];
        $start_time = $_POST['start_time'] . ':00';
        $end_time = $_POST['end_time'] . ':00';

        // Validate times (start < end)
        if (strtotime($start_time) >= strtotime($end_time)) {
            setMessage('error', 'Start time must be before end time.');
            redirect("admin/addtimeslot/$doctor_id");
            return;
        }

        $timeslot = new TimeslotModel();
        $timeslot->setDay($day);
        $timeslot->setStartTime($start_time);
        $timeslot->setEndTime($end_time);
        $timeslot->setIsBooked(0);
        $timeslot->setUserId($doctor_id);

        $created = $this->db->create('timeslots', $timeslot->toArray());

        if ($created) {
            setMessage('success', 'Timeslot added successfully.');
        } else {
            setMessage('error', 'Failed to add timeslot.');
        }
        redirect("admin/viewtimeslots/$doctor_id");
    } else {
        // Show form to add timeslot
        $this->view('admin/addtimeslot', ['doctor_id' => $doctor_id]);
    }
}

    public function viewTimeslots($doctor_id) {
        $timeslots = $this->db->columnFilterAll('timeslots', 'user_id', $doctor_id);
        $this->view('admin/timeslotlist', ['timeslots' => $timeslots, 'doctor_id' => $doctor_id]);
    }

    public function deletetimeslot($timeslot_id, $doctor_id) {
        $this->db->delete('timeslots', $timeslot_id);
        setMessage('success', 'Timeslot deleted.');
        redirect("admin/viewtimeslots/$doctor_id");
    }


    // You can add more methods here, like deleteTimeslotsByUser(), getTimeslotsByUser(), etc.
}
