<?php
class Timeslot extends Controller
{
    private $db;

    public function __construct()
    {
        $this->model('TimeslotModel');
        $this->db = new Database();
    }

    //    public function generateTimeSlots($start, $end, $duration = 20)
    // {
    //     $slots = [];
    //     $current = strtotime($start);
    //     $endTime = strtotime($end);

    //     while ($current < $endTime) { 
    //         $slots[] = date("H:i", $current);
    //         $current = strtotime("+$duration minutes", $current);
    //     }
    //     return $slots;
    }