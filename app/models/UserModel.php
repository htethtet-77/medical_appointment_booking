<?php
namespace Asus\Medical\models;
use Asus\Medical\models\BaseModel;
// require_once "BaseModel.php";
class UserModel extends BaseModel
{
    // Access Modifier = public, private, protected
    protected $name;
    protected $gender;
    protected $email;
    protected $phone;
    protected $password;
    protected $profile_image;
    protected $is_confirmed;
    protected $is_active;
    protected $is_login;

    protected $type_id;
    protected $status_id;
    
}