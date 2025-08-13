<?php
// TestableAppointment.php
require_once __DIR__ . '/../app/libraries/Core.php';

require_once __DIR__ . '/../app/controllers/Appointment.php';

class TestableAppointment extends Appointment
{
    public $viewData = null;

    public function __construct($service)
    {
        $this->service = $service;
    }

    public function view($view, $data = [])
    {
        // Instead of rendering, capture parameters for assertions
        $this->viewData = [
            'view' => $view,
            'data' => $data,
        ];
    }
}
