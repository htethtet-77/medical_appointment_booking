<?php

class UserModel
{
    // Access Modifier = public, private, protected
    private $name;
    private $gender;
    private $email;
    private $phone;
    private $password;
    private $profile_image;
    private $is_confirmed;
    private $is_active;
    private $is_login;
    // private $date;
    private $type_id;
    private $status_id;
    

    public function setName($name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
  
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function getEmail()
    {
        return $this->email;
    }
        public function setGender($gender)
    {
        $this->gender = $gender;
    }

    public function getGender()
    {
        return $this->gender;
    }

    public function setPhone($phone)
    {
        $this->phone = $phone;
    }
    public function getPhone()
    {
        return $this->phone;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getPassword()
    {
        return $this->password;
    }

    public function setProfileImage($profile_image)
    {
        $this->profile_image = $profile_image;
    }
    public function getProfileImage()
    {
        return $this->profile_image;
    }

    public function setIsConfirmed($is_confirmed)
    {
        $this->is_confirmed = $is_confirmed;
    }
    public function getIsConfirmed()
    {
        return $this->is_confirmed;
    }

    public function setIsActive($is_active)
    {
        $this->is_active = $is_active;
    }
    public function getIsActive()
    {
        return $this->is_active;
    }

    public function setIsLogin($is_login)
    {
        $this->is_login = $is_login;
    }
    public function getIsLogin()
    {
        return $this->is_login;
    }

     public function setTypeId($type_id)
    {
        $this->type_id = $type_id;
    }

    public function getTypeId()
    {
        return $this->type_id;
    }

    public function setStatusId($status_id)
    {
        $this->status_id = $status_id;
    }

    public function getStatusId()
    {
        return $this->status_id;
    }

    // public function setDate($date)
    // {
    //     $this->date = $date;
    // }
    // public function getDate()
    // {
    //     return $this->date;
    // }

    public function toArray() {
        return [
            "name" => $this->getName(),
            "email" => $this->getEmail(),
            "gender"=> $this->getGender(),
            "phone" => $this->getPhone(),
            "password" => $this->getPassword(),
            "profile_image" => $this->getProfileImage(),
            "is_confirmed" => $this->getIsConfirmed(),
            "is_active" => $this->getIsActive(),
            "is_login" => $this->getIsLogin(),
            "type_id" => $this->getTypeId(),
            "status_id" => $this->getStatusId()
            // "date" => $this->getDate()
        ];
    }
}