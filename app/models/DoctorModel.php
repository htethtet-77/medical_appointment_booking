<?php

class DoctorModel {
    private $id;
    private $degree;
    private $experience;
    private $bio;
    private $service;
    private $specialty;
    private $user_id;
 public function setId($id)
    {
        $this->id = $id;
    }
    public function getId()
    {
        return $this->id;
    }

    public function setDegree($degree)
    {
        $this->degree = $degree;
    }
    public function getDegree()
    {
        return $this->degree;
    }

    public function setExperience($experience)
    {
         if (!is_numeric($experience) || $experience < 0) {
        throw new Exception("Experience must be a non-negative number.");
    }
        $this->experience = $experience;
    }
    public function getExperience()
    {
        return $this->experience;
    }

    public function setBio($bio)
    {
        $this->bio = $bio;
    }
    public function getBio() 
    {
        return $this->bio;
    }

    public function setService($service)
    {
        $this->service = $service;
    }
    public function getService() 
    {
        return $this->service;
    }

    public function setSpecialty($specialty)
    {
        $this->specialty = $specialty;
    }
    public function getSpecialty() 
    {
        return $this->specialty;
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
            "id"    => $this->getId(),
            "degree" => $this->getDegree(),
            "experience" => $this->getExperience(),
            "bio" => $this->getBio(),
            "service"  => $this->getService(),
            "specialty"  => $this->getSpecialty(),
            "user_id" => $this->getUserId()
        ];
    }
}
