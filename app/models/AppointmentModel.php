<?php

class AppointmentModel {
    private $doctor_id;
    private $created_at;
    private $appointment_date;

    private $appointment_time;
    private $reason;
    private $timeslot_id;
    private $user_id;
     private $status_id;
 public function setDoctorId($doctor_id)
    {
        $this->doctor_id = $doctor_id;
    }
    public function getDoctorId()
    {
        return $this->doctor_id;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at= $created_at;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }
     public function setAppointmentDate($appointment_date)
    {
        $this->appointment_date = $appointment_date;
    }
    public function getAppointmentDate()
    {
        return $this->appointment_date;
    }

    public function setAppointmentTime($appointment_time)
    {
        $this->appointment_time = $appointment_time;
    }
    public function getAppointmentTime()
    {
        return $this->appointment_time;
    }
    public function setReason($reason)
    {
        $this->reason = $reason;
    }
    public function getReason()
    {
        return $this->reason;
    }

    public function setTimeslotId($timeslot_id)
    {
        $this->timeslot_id = $timeslot_id;
    }
     public function getTimeslotId()
    {
        return $this->timeslot_id;
    }

     public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }
     public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    public function toArray()
    {
        return [
            "doctor_id"    => $this->getDoctorId(),
            "created_at" => $this->getCreatedAt(),
            "appointment_date" => $this->getAppointmentDate(),
            "appointment_time" => $this->getAppointmentTime(),
            "reason" => $this->getReason(),
            "timeslot_id" => $this->getTimeslotId(),
            "user_id" => $this->getUserId(),
            "status_id" => $this->getStatusId()
        ];
    }
}
?>
