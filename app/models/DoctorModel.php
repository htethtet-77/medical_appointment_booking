<?php

class Doctor {
    private $id;
    private $name;
    private $specialty;
    private $experience;
    private $phone;
    private $image;
    private $description;

    // --- Getters ---
    public function getId() { return $this->id; }
    public function getName() { return $this->name; }
    public function getSpecialty() { return $this->specialty; }
    public function getExperience() { return $this->experience; }
    public function getPhone() { return $this->phone; }
    public function getImage() { return $this->image; }
    public function getDescription() { return $this->description; }

    // --- Setters ---
    public function setId($id) { $this->id = $id; }
    public function setName($name) { $this->name = $name; }
    public function setSpecialty($specialty) { $this->specialty = $specialty; }
    public function setExperience($experience) { $this->experience = $experience; }
    public function setPhone($phone) { $this->phone = $phone; }
    public function setImage($image) { $this->image = $image; }
    public function setDescription($description) { $this->description = $description; }
}
