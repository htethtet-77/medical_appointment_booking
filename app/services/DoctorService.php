<?php


class DoctorService {

    protected $db;

    public function __construct() {
        $this->model("UserModel");
        $this->db = new Database();
    }

    // ✅ Reusable Image Upload
    public function uploadImage($fileKey, $prefix = 'doctor_', $uploadDir = 'public/image/') {
        if (empty($_FILES[$fileKey]['name']) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            return false;
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        $imageName  = uniqid($prefix) . '_' . basename($_FILES[$fileKey]['name']);
        $targetPath = $uploadDir . $imageName;

        return move_uploaded_file($_FILES[$fileKey]['tmp_name'], $targetPath) 
            ? $targetPath 
            : false;
    }

    // ✅ Reusable User Update
    public function updateUser($id, $data, $imagePath) {
        $user = new UserModel();
        $user ->setName($data['name']);
        $user->setEmail($data['email']);
        $user ->setPhone($data['phone'])
            ->setGender($data['gender'])
            ->setPassword($data['password'])
            ->setProfileImage($imagePath)
            ->setIsLogin(0)
            ->setIsActive(0)
            ->setIsConfirmed(0)
            ->setTypeId(2)
            ->setStatusId(2);

        return $this->db->update('users', $id, $user->toArray());
    }

    // ✅ Reusable Doctor Profile Update
    public function updateDoctorProfile($id, $data) {
        $doctor = (new DoctorModel())
            ->setDegree($data['degree'])
            ->setExperience($data['experience'])
            ->setBio($data['bio'])
            ->setFee($data['fee'])
            ->setSpecialty($data['specialty'])
            ->setAddress($data['address'])
            ->setUserId($id);

        $existingDoctor = $this->db->columnFilter('doctorprofile', 'user_id', $id);

        return $existingDoctor
            ? $this->db->update('doctorprofile', $existingDoctor['id'], $doctor->toArray())
            : $this->db->create('doctorprofile', $doctor->toArray());
    }

    // ✅ Reusable Timeslot Update
    public function updateTimeslot($id, $data) {
        $timeslot = (new TimeslotModel())
            ->setDay($data['day'])
            ->setStartTime($data['start_time'])
            ->setEndTime($data['end_time'])
            ->setIsBooked(0)
            ->setUserId($id);

        $timeslotRow = $this->db->columnFilter('timeslots', 'user_id', $id);

        return $timeslotRow
            ? $this->db->update('timeslots', $timeslotRow['id'], $timeslot->toArray())
            : $this->db->create('timeslots', $timeslot->toArray());
    }
}
