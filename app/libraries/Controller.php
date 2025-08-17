<?php
// load model and views
namespace Asus\Medical\libraries;
class Controller
{
    // Load Model
    public function model($model) // Product
    {
        $modelFQN = "\\Asus\\Medical\\models\\$model"; // Fully-qualified namespace
        require_once '../app/models/' . $model . '.php';
        // return new $model();
        return new $modelFQN();
    }
    // Load views
    public function view($view, $data = [])
    {
        if (file_exists('../app/views/' . $view . '.php')) {
            require_once('../app/views/' . $view . '.php');
        } else {
            die('View does not exist');
        }
    }
}
