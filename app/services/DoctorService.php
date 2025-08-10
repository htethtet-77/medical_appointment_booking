<?php
require_once __DIR__ . '/../repositories/DoctorRepository.php';

class DoctorService
{
    private $doctorRepository;

    public function __construct(DoctorRepository $doctorRepository)
    {
        $this->doctorRepository = $doctorRepository;
    }

    public function getDoctorDashboardData(int $doctorUserId): array
    {
        $allAppointments = $this->doctorRepository->getAppointmentByDoctorId($doctorUserId);

        if (!is_array($allAppointments)) {
        $allAppointments = [];
    }

        $appointmentsByDate = [];
        $totalAppointments = 0;
        $todaysAppointments = 0;
        $dateString = (new DateTime())->format('Y-m-d');

        foreach ($allAppointments as $appointment) {
            $appointmentDate = date('Y-m-d', strtotime($appointment['appointment_date']));

            if (!isset($appointmentsByDate[$appointmentDate])) {
                $appointmentsByDate[$appointmentDate] = [];
            }
            $appointmentsByDate[$appointmentDate][] = $appointment;
            $totalAppointments++;

            if ($appointmentDate === $dateString) {
                $todaysAppointments++;
            }
        }

        $totalPatients = count(array_unique(array_column($allAppointments, 'patient_id')));

        krsort($appointmentsByDate);

        return [
            'appointmentsByDate' => $appointmentsByDate,
            'todayDate' => $dateString,
            'totalAppointments' => $totalAppointments,
            'todaysAppointments' => $todaysAppointments,
            'totalPatients' => $totalPatients,
        ];
    }
}
