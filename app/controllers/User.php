<?php
session_start();
class User extends Controller
{
    private $db;
    public function __construct()
    {
        $this->model('UserModel');
        $this->db = new Database();
    }
    public function profile()
    {   
  
        $this->view('pages/userprofile');
    }



}
?>