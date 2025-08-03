<?php

class TimeslotModel {
    private $day;
    private $start_time;
    private $end_time;
    // private $is_booked;
    private $user_id;
 

    public function setStartTime($start_time)
    {
        $this->start_time = $start_time;
    }
    public function getStartTime()
    {
        return $this->start_time;
    }

    public function setEndTime($end_time)
    {
        $this->end_time = $end_time;
    }
    public function getEndTime()
    {
        return $this->end_time;
    }

    // public function setIsBooked($is_booked)
    // {
    //     $this->is_booked = $is_booked;
    // }
    // public function getIsBooked()
    // {
    //     return $this->is_booked;
    // }

    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function getUserId()
    {
        return $this->user_id;
    }

    public function toArray()
    {
        return [
          
            "start_time" => $this->getStartTime(),
            "end_time" => $this->getEndTime(),
            // "is_booked" => $this->getIsBooked(),
            "user_id" => $this->getUserId()
        ];
    }
}
