<?php
require_once "BaseModel.php";
class AppointmentModel extends BaseModel{
    protected $doctor_id;
    protected $created_at;
    protected $appointment_date;

    protected $appointment_time;
    protected $reason;
    protected $timeslot_id;
    protected $user_id;
    protected $status_id;
}
