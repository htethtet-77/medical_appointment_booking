<?php

class TimeslotModel {
    private $date;
    private $time;
    private $is_available;
    private $user_id;

    public function setDate($date)
    {
        $this->date = $date;
    }
    public function getDate()
    {
        return $this->date;
    }

    public function setTime($time)
    {
        $this->time = $time;
    }
    public function getTime()
    {
        return $this->time;
    }

    public function setIsAvailable($is_available)
    {
        $this->is_available = $is_available;
    }
    public function getIsAvailable()
    {
        return $this->is_available;
    }

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
            "date" => $this->getDate(),
            "time" => $this->getTime(),
            "is_available" => $this->getIsAvailable(),
            "user_id" => $this->getUserId()
        ];
    }
}
