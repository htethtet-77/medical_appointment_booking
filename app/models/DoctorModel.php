<?php

class DoctorModel {

    private $degree;
    private $experience;
    private $bio;
    private $fee;

    private $specialty;
    private $address;
  
    private $user_id;


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
    public function setFee($fee)
    {
        $this->fee = $fee;
    }
    public function getFee() 
    {
        return $this->fee;
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
     public function setAddress($address)
    {
        $this->address = $address;
    }
    public function getAddress()
    {
        return $this->address;
    }
    public function toArray()
    {
        return [
            // "id"    => $this->getId(),
            "degree" => $this->getDegree(),
            "experience" => $this->getExperience(),
            "bio" => $this->getBio(),
            "fee" => $this->getFee(),
            "specialty"  => $this->getSpecialty(),
            "address"  => $this->getAddress(),
            "user_id" => $this->getUserId()
        ];
    }
}
