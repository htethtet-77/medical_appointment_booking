<?php
// use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../app/interfaces/AdminServiceInterface.php';
require_once __DIR__ . '/../app/interfaces/AdminRepositoryInterface.php';
require_once __DIR__ . '/../app/interfaces/ImageUploadServiceInterface.php';
require_once __DIR__ . '/../app/services/ImageUploadService.php';
require_once __DIR__ . '/../app/services/AdminService.php';


final class AdminServiceTest extends PHPUnit\Framework\TestCase
{
    private $repo;
    private $uploader;
    private AdminService $service;

    protected function setUp(): void
    {
        // PHPUnit mocks
        $this->repo = $this->createMock(AdminRepositoryInterface::class);
        $this->uploader = $this->createMock(ImageUploadServiceInterface::class);

        $this->service = new AdminService($this->repo, $this->uploader);
    }

    private function validDoctorPost(): array
    {
        return [
            'name' => 'Dr. Alice',
            'email' => 'alice@example.com',
            'phone' => '0912345678',
            'gender' => 'female',
            'password' => 'secret',
            'degree' => 'MBBS',
            'experience' => '5',
            'bio' => 'Cardiologist',
            'fee' => '50000',
            'specialty' => 'Cardiology',
            'address' => 'Yangon',
            'start_time' => '09:00',
            'end_time' => '17:00',
        ];
    }

    private function validFile(): array
    {
        return [
            'tmp_name' => sys_get_temp_dir() . '/dummy.jpg',
            'error' => UPLOAD_ERR_OK,
            'name' => 'dummy.jpg',
            'type' => 'image/jpeg',
            'size' => 1234,
        ];
    }

    public function testAddDoctorSuccess(): void
    {
        $data = $this->validDoctorPost();
        $file = $this->validFile();

        // No duplicates
        $this->repo->method('emailExists')->with('alice@example.com')->willReturn(false);
        $this->repo->method('phoneExists')->with('0912345678')->willReturn(false);

        // Image upload returns a filename
        $this->uploader->expects($this->once())
            ->method('upload')
            ->with($file, $this->callback(fn($dir) => str_contains($dir, '/public/image/')), 'doctor_')
            ->willReturn('doctor_abc.jpg');

        // Verify addDoctor receives properly mapped params
        $this->repo->expects($this->once())
            ->method('addDoctor')
            ->with($this->callback(function($params) use ($data) {
                // order based on your AdminService::$params assembly
                [$name,$email,$phone,$gender,$password,$image,$degree,$exp,$bio,$fee,$spec,$addr,$start,$end] = $params;

                // base64 password check
                if ($password !== base64_encode(trim($data['password']))) return false;

                return $name === $data['name']
                    && $email === $data['email']
                    && $phone === $data['phone']
                    && $gender === $data['gender']
                    && $image === 'image/doctor_abc.jpg'
                    && $degree === $data['degree']
                    && $exp === (int)$data['experience']
                    && $bio === $data['bio']
                    && $fee === $data['fee']
                    && $spec === $data['specialty']
                    && $addr === $data['address']
                    && $start === $data['start_time'] . ':00'
                    && $end === $data['end_time'] . ':00';
            }))
            ->willReturn(42);

        $id = $this->service->addDoctor($data, $file);
        $this->assertSame(42, $id);
    }

    public function testAddDoctorDuplicateEmailThrows(): void
    {
        $data = $this->validDoctorPost();
        $this->repo->method('emailExists')->with('alice@example.com')->willReturn(true);

        $this->expectExceptionMessage('This email is already registered!');
        $this->service->addDoctor($data, []);
    }

    public function testAddDoctorDuplicatePhoneThrows(): void
    {
        $data = $this->validDoctorPost();
        $this->repo->method('emailExists')->willReturn(false);
        $this->repo->method('phoneExists')->with('0912345678')->willReturn(true);

        $this->expectExceptionMessage('Phone number already exists!');
        $this->service->addDoctor($data, []);
    }

    public function testAddDoctorInvalidTimeThrows(): void
    {
        $data = $this->validDoctorPost();
        $data['start_time'] = '18:00';
        $data['end_time'] = '17:00';

        $this->repo->method('emailExists')->willReturn(false);
        $this->repo->method('phoneExists')->willReturn(false);

        $this->expectExceptionMessage('Start time must be before end time.');
        $this->service->addDoctor($data, []);
    }

    public function testDeleteDoctorCascade(): void
    {
        $userId = 7;
        $this->repo->expects($this->once())
            ->method('findTimeslotsByUserId')->with($userId)
            ->willReturn(['id' => 88]);

        $this->repo->expects($this->once())->method('deleteTimeslot')->with(88);

        $this->repo->expects($this->once())
            ->method('findAppointmentsByTimeslotId')->with(88)
            ->willReturn([
                ['id' => 1], ['id' => 2]
            ]);

        $this->repo->expects($this->exactly(2))
            ->method('deleteAppointment')
            ->withConsecutive([1],[2]);

        $this->repo->expects($this->once())
            ->method('findDoctorProfileByUserId')->with($userId)
            ->willReturn(['id' => 55]);

        $this->repo->expects($this->once())->method('deleteDoctorProfile')->with(55);

        $this->repo->expects($this->once())->method('deleteUser')->with($userId)->willReturn(true);

        $ok = $this->service->deleteDoctor($userId);
        $this->assertTrue($ok);
    }

    public function testGetDoctorDetailsAggregates(): void
    {
        $uid = 10;
        $this->repo->method('findUserById')->with($uid)->willReturn(['id' => $uid, 'name' => 'Dr. X']);
        $this->repo->method('findDoctorProfileByUserId')->with($uid)->willReturn(['id' => 99, 'degree' => 'MD']);
        $this->repo->method('findTimeslotsByUserId')->with($uid)->willReturn(['id' => 77]);

        $out = $this->service->getDoctorDetails($uid);
        $this->assertSame(['id' => 10, 'name' => 'Dr. X'], $out['users']);
        $this->assertSame(['id' => 99, 'degree' => 'MD'], $out['doctorprofile']);
        $this->assertSame(['id' => 77], $out['timeslots']);
    }

    public function testUpdateDoctorKeepsPasswordAndImageIfNotProvided(): void
    {
        $data = $this->validDoctorPost();
        $data['id'] = 3;
        $data['password'] = ''; // simulate empty password -> keep existing

        $existing = [
            'id' => 3, 'password' => base64_encode('oldpass'), 'profile_image' => 'image/old.jpg'
        ];

        $this->repo->method('findUserById')->with(3)->willReturn($existing);

        $this->uploader->expects($this->never())->method('upload');

        $this->repo->expects($this->once())
            ->method('updateDoctor')
            ->with($this->callback(function($params) {
                [$id,$name,$email,$phone,$gender,$password,$image] = array_slice($params, 0, 7);
                return $id === 3
                    && $password === base64_encode('oldpass') // kept
                    && $image === 'image/old.jpg';
            }));

        $this->assertTrue($this->service->updateDoctor($data, []));
    }

    public function testUpdateDoctorUploadsNewImage(): void
    {
        $data = $this->validDoctorPost();
        $data['id'] = 4;

        $existing = [
            'id' => 4, 'password' => base64_encode('oldpass'), 'profile_image' => 'image/old.jpg'
        ];

        $file = $this->validFile();

        $this->repo->method('findUserById')->with(4)->willReturn($existing);

        $this->uploader->expects($this->once())
            ->method('upload')
            ->willReturn('doctor_new.jpg');

        $this->repo->expects($this->once())
            ->method('updateDoctor')
            ->with($this->callback(function($params) use ($data) {
                [$id,$name,$email,$phone,$gender,$password,$image] = array_slice($params, 0, 7);
                return $id === 4 && $image === 'image/doctor_new.jpg';
            }));

        $this->assertTrue($this->service->updateDoctor($data, $file));
    }

    public function testGetDashboardDataAggregatesCounts(): void
    {
        // Two appointments today (use PHP's date to match service)
        $today = date('Y-m-d');

        $appointments = [
            ['patient_id' => 1, 'appointment_date' => $today . ' 10:00:00'],
            ['patient_id' => 1, 'appointment_date' => $today . ' 12:00:00'],
            ['patient_id' => 2, 'appointment_date' => '2025-01-01 09:00:00'],
        ];

        $this->repo->method('getAppointments')->willReturn($appointments);

        $out = $this->service->getDashboardData();

        $this->assertSame(3, $out['totalAppointments']);
        $this->assertSame(2, $out['todaysAppointments']);
        $this->assertSame(2, $out['totalPatients']); // unique patients: 1 & 2
        $this->assertSame($today, $out['todayDate']);
        $this->assertArrayHasKey($today, $out['appointmentsByDate']);
        $this->assertCount(2, $out['appointmentsByDate'][$today]);
    }
}
