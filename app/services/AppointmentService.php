<?php
require_once __DIR__ . '/../repositories/AppointmentRepository.php';
require_once __DIR__ . '/../interfaces/AppointmentRepositoryInterface.php';

require_once __DIR__ . '/../models/AppointmentModel.php';

class AppointmentService
{
    private AppointmentRepositoryInterface $repo;

    public function __construct(AppointmentRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function getAvailableSlotsForDoctor(int $doctorId, string $selectedDate): array
    {
        require_once APPROOT .'/helpers/appointment_helper.php';
        $doctor = $this->repo->findDoctorById($doctorId);
        if (!$doctor) {
            throw new Exception("Doctor not found");
        }

        $timeslot = $this->repo->findTimeslotByDoctorId($doctorId);
        if (!$timeslot) {
            throw new Exception("Doctor timeslot not found");
        }

        $slots = AppointmentHelper::getAvailableSlots($timeslot['start_time'], $timeslot['end_time']);
        $appointments = $this->repo->findAppointmentsByDoctorId($doctorId);
        $bookedTimes = AppointmentHelper::getBookedTimes($appointments, $selectedDate);
        return AppointmentHelper::filterFutureAvailableSlots($slots, $bookedTimes, $selectedDate);
    }

    public function getDoctorById(int $doctorId)
    {
        $doctor = $this->repo->findDoctorById($doctorId);
        if (!$doctor) {
            throw new Exception("Doctor not found");
        }
        return $doctor;
    }


    public function bookAppointment(array $data): bool
    {
        // Validate mandatory fields here or in controller

        // Check for duplicate
        $appointments = $this->repo->findAppointmentsByDoctorId($data['doctor_id']);
        foreach ($appointments as $a) {
            if ($a['appointment_date'] === $data['appointment_date'] && $a['appointment_time'] === $data['appointment_time']) {
                throw new Exception("Timeslot already booked");
            }
        }

        return $this->repo->bookAppointment([
            $data['doctor_id'],
            $data['patient_id'],
            $data['timeslot_id'], 
            $data['appointment_date'],
            $data['appointment_time'],
            $data['reason'],
            2
        ]);

    }

    public function getAppointmentsByPatient(int $patientId): array
    {
        return $this->repo->findAppointmentsByPatientId($patientId);
    }

    public function cancelAppointment(int $appointmentId): bool
    {
        $appointment = $this->repo->findAppointmentById($appointmentId);
        if (!$appointment) {
            throw new Exception("Appointment not found");
        }
        if (in_array($appointment['status_id'], [1, 3])) {
            throw new Exception("Cannot cancel confirmed or rejected appointment");
        }
        return $this->repo->deleteAppointment($appointmentId);
    }

    public function updateAppointmentStatus(int $appointmentId, int $statusId): bool
    {
        $appointment = $this->repo->findAppointmentById($appointmentId);
        if (!$appointment) {
            throw new Exception("Appointment not found");
        }

        $model = new AppointmentModel();
        $model->created_at = $appointment['created_at'];
        $model->reason = $appointment['reason'];
        $model->timeslot_id = $appointment['timeslot_id'];
        $model->appointment_date = $appointment['appointment_date'];
        $model->appointment_time = $appointment['appointment_time'];
        $model->user_id = $appointment['user_id'];
        $model->doctor_id = $appointment['doctor_id'];
        $model->status_id = $statusId;

        return $this->repo->updateAppointment($appointmentId, $model->toArray());
    }

   
}
