<?php

class AppointmentModel {
    private $id;
    private $created_at;
    private $appointment_fee;
    private $timeslot_id;
    private $user_id;
     private $status_id;
 public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setCreatedAt($created_at)
    {
        $this->created_at= $created_at;
    }
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function setAppointmentFee($appointment_fee)
    {
        $this->appointment_fee = $appointment_fee;
    }
    public function getAppointmentFee()
    {
        return $this->appointment_fee;
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
            "id"    => $this->getId(),
            "created_at" => $this->getCreatedAt(),
            "appointment_fee" => $this->getAppointmentFee(),
            "timeslot_id" => $this->getTimeslotId(),
            "user_id" => $this->getUserId(),
            "status_id" => $this->getStatusId()
        ];
    }
}
