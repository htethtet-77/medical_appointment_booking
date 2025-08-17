<?php
namespace Asus\Medical\Tests;
require_once __DIR__ . '/../app/config/config.php';

use PHPUnit\Framework\TestCase;
use Asus\Medical\Services\AdminService;
use Asus\Medical\Interfaces\AdminRepositoryInterface;
use Asus\Medical\Interfaces\ImageUploadServiceInterface;
use Exception;

final class AdminServiceTest extends TestCase
{
    private $repo;
    private $uploader;
    private AdminService $service;

    protected function setUp(): void
    {
        // Create mocks for dependencies
        $this->repo = $this->createMock(AdminRepositoryInterface::class);
        $this->uploader = $this->createMock(ImageUploadServiceInterface::class);

        // Manually inject mocks into the service
        $this->service = new AdminService($this->repo, $this->uploader);
    }

   private function validDoctorPost(): array
{
    return [
        'name' => 'Dr. Alice',
        'email' => 'alice@example.com',
        'phone' => '0912345678',
        'gender' => 'female',
        'password' => 'Alice123@', // ✅ valid password
        'degree' => 'MBBS',
        'experience' => '5',
        'bio' => 'General Physician',
        'fee' => '50000',
        'specialty' => 'General Physician',
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

        // Mock repository methods
        $this->repo->method('emailExists')->with($data['email'])->willReturn(false);
        $this->repo->method('phoneExists')->with($data['phone'])->willReturn(false);

        // Mock uploader
        $this->uploader->expects($this->once())
            ->method('upload')
            ->with(
                $file,
                $this->callback(fn($dir) => str_contains($dir, '/public/image/')),
                'doctor_'
            )
            ->willReturn('doctor_abc.jpg');

        // Mock addDoctor repository call
        $this->repo->expects($this->once())
            ->method('addDoctor')
            ->with($this->callback(function($params) use ($data) {
                [$name,$email,$phone,$gender,$password,$image] = $params;
                return $name === $data['name']
                    && $email === $data['email']
                    && $phone === $data['phone']
                    && $password === base64_encode(trim($data['password']))
                    && $image === 'image/doctor_abc.jpg';
            }))
            ->willReturn(42);// fake database doctor insert ID

        $id = $this->service->addDoctor($data, $file);
        $this->assertSame(42, $id);//mock wasn’t called correctly, or the callback returned false, PHPUnit would fail the test before reaching this line.
    }

    public function testAddDoctorDuplicateEmailThrows(): void
    {
        $data = $this->validDoctorPost();
        $this->repo->method('emailExists')->with($data['email'])->willReturn(true);

        $this->expectExceptionMessage('This email is already registered!');
        $this->service->addDoctor($data, []);//the file  isn’t needed because the service should fail validation before ever touching the uploader.
    }

    public function testAddDoctorDuplicatePhoneThrows(): void
    {
        $data = $this->validDoctorPost();
        $this->repo->method('emailExists')->willReturn(false);
        $this->repo->method('phoneExists')->with($data['phone'])->willReturn(true);

        $this->expectExceptionMessage('Phone number already exists!');
        $this->service->addDoctor($data, []);
    }
    public function testGetAllDoctors():void{
        $data = $this->validDoctorPost();
        $this->repo->expects($this->once())
            ->method('getAllDoctors')
            ->willReturn([$data]);
        $result=$this->service->getAllDoctors();
        $this->assertEquals([$data],$result);
    }
    public function testDeleteDoctorMissingUserId():void{
       $this->expectException(Exception::class);
       $this->expectExceptionMessage('User ID missing.');
       $this->service->deleteDoctor(null);

    }
    public function testDeleteDoctorSuccess(): void
    {
        $userId = 1;

        // Mock timeslots
        $timeslot = ['id' => 100];
        $this->repo->method('findTimeslotsByUserId')->with($userId)->willReturn($timeslot);

        // Mock appointments
        $appointments = [
            ['id' => 101],
            ['id' => 102]
        ];
        $this->repo->method('findAppointmentsByTimeslotId')->with($timeslot['id'])->willReturn($appointments);

        // Mock deleteAppointment to check called with either 101 or 102
        $called = [];
        $this->repo->method('deleteAppointment')->willReturnCallback(function($id) use ($appointments, &$called) {
            if (!in_array($id, array_column($appointments, 'id'))) {
                throw new Exception("Unexpected appointment ID $id");
            }
            $called[] = $id;
            return true;
        });

        // Mock deleteTimeslot
        $this->repo->expects($this->once())->method('deleteTimeslot')->with($timeslot['id']);

        // Mock doctor profile deletion
        $doctorProfile = ['id' => 200];
        $this->repo->method('findDoctorProfileByUserId')->with($userId)->willReturn($doctorProfile);
        $this->repo->expects($this->once())->method('deleteDoctorProfile')->with($doctorProfile['id']);

        // Mock final user deletion
        $this->repo->expects($this->once())->method('deleteUser')->with($userId)->willReturn(true);

        // Call service
        $result = $this->service->deleteDoctor($userId);

        // Assert
        $this->assertTrue($result);
        $this->assertEquals([101, 102], $called); // Ensure both appointments deleted
    }

    public function testDeleteDoctorFails(): void
    {
        $userId = 1;

        $this->repo->method('findTimeslotsByUserId')->willReturn([]);
        $this->repo->method('findDoctorProfileByUserId')->willReturn(null);
        $this->repo->method('deleteUser')->with($userId)->willReturn(false);

        $this->expectExceptionMessage('Failed to delete doctor.');
        $this->service->deleteDoctor($userId);
    }
    public function testUpdateDoctor()
    {
        $data = $this->validDoctorPost();
        $data['id'] = 1;
        $data['password'] = 'NewPass123@'; // new password
        $file = [
            'tmp_name' => '/tmp/fakefile.jpg',
            'error' => UPLOAD_ERR_OK
        ];

        $existingUser = [
            'id' => 1,
            'password' => 'oldpassword',
            'profile_image' => 'image/old.jpg'
        ];

        $this->repo->expects($this->once())
            ->method('findUserById')
            ->with(1)
            ->willReturn($existingUser);

        $this->uploader->expects($this->once())
            ->method('upload')
            ->with($file, $this->anything(), 'doctor_')
            ->willReturn('new_image.jpg');

        $this->repo->expects($this->once())
            ->method('updateDoctor')
            ->with($this->callback(function($params) use ($data) {
                // Check every field
                return $params[0] === $data['id'] &&
                    $params[1] === $data['name'] &&
                    $params[2] === $data['email'] &&
                    $params[3] === $data['phone'] &&
                    $params[4] === $data['gender'] &&
                    $params[5] === base64_encode($data['password']) &&
                    $params[6] === 'image/new_image.jpg' &&
                    $params[7] === $data['degree'] &&
                    $params[8] === (int)$data['experience'] &&
                    $params[9] === $data['bio'] &&
                    $params[10] === $data['fee'] &&
                    $params[11] === $data['specialty'] &&
                    $params[12] === $data['address'] &&
                    $params[13] === $data['start_time'] . ':00' &&
                    $params[14] === $data['end_time'] . ':00';
            }));

        $result = $this->service->updateDoctor($data, $file);
        $this->assertTrue($result);
    }
    public function testGetDoctorDetailsSuccess():void{
        $UserId=1;
        $user = $this->validDoctorPost();
        $user['id']=$UserId;
        $doctorProfile = [
        'id' => 10,
        'degree' => $user['degree'],
        'experience' => $user['experience'],
        'bio' => $user['bio'],
        'fee' => $user['fee'],
        'specialty' => $user['specialty'],
        'address' => $user['address'],
        'start_time' => $user['start_time'] . ':00',
        'end_time' => $user['end_time'] . ':00',
    ];
       $timeslots = [
        ['id' => 100, 'user_id' => $UserId, 'start_time' => '09:00', 'end_time' => '12:00'],
        ['id' => 101, 'user_id' => $UserId, 'start_time' => '13:00', 'end_time' => '17:00'],
    ];
        $this->repo->expects($this->once())
             ->method('findUserById')
             ->with($UserId)->willReturn($user);
        $this->repo->expects($this->once())
             ->method('findDoctorProfileByUserId')
             ->with($UserId)->willReturn($doctorProfile);
        $this->repo->expects($this->once())
             ->method('findTimeslotsByUserId')
             ->with($UserId)->willReturn($timeslots);
        $result=$this->service->getDoctorDetails($UserId);
        $this->assertEquals($user, $result['users']);
        $this->assertEquals($doctorProfile, $result['doctorprofile']);
        $this->assertEquals($timeslots, $result['timeslots']);
    }
    public function testGetPatient():void{
        // $data = $this->validDoctorPost();
        $patient=['name' => ' Alice',
        'email' => 'alice@example.com',
        'phone' => '0912345678',
        'gender' => 'female',
        'password' => 'Alice123@'];
        $this->repo->expects($this->once())
             ->method('getPatients')
             ->willReturn($patient);
        $result=$this->service->getPatients();
        $this->assertEquals($patient,$result);
    }
    public function testGetAppointment():void{
        $appointments=[
            'id' => 1,
            'created_at' => '2025-08-15 10:30:00',
            'appointment_date' => '2025-08-20',
            'appointment_time' => '14:00:00',
            'reason' => 'General Checkup',
            'timeslot_id' => 10,
            'user_id' => 100,
            'doctor_id' => 200,
            'status_id' => 1,
        ];
        $this->repo->expects($this->once())
             ->method('getAppointments')
             ->willReturn($appointments);
        $result=$this->service->getAppointments();
        $this->assertEquals($appointments, $result);
        $this->assertEquals('General Checkup', $result['reason']); // spot check
    }
   public function testGetDashboardDataSuccess()
    {
        // Freeze "today" to a known date so the test is predictable
        $today = '2025-08-15';
        
        // Fake appointments: 3 total, 2 today, 1 on another day
        $appointments = [
            [
                'id' => 1,
                'appointment_date' => $today,
                'patient_id' => 101,
            ],
            [
                'id' => 2,
                'appointment_date' => $today,
                'patient_id' => 102,
            ],
            [
                'id' => 3,
                'appointment_date' => '2025-08-14',
                'patient_id' => 101, // same patient as first record
            ],
        ];

        // Mock repository to return fake appointments
        $this->repo->method('getAppointments')->willReturn($appointments);

        // Call the service method
        $result = $this->service->getDashboardData();

        // Expected calculations:
        // - totalAppointments = 3
        // - todaysAppointments = 2 (2 matching today's date)
        // - totalPatients = 2 (patient_id 101 and 102 are unique)
        // - appointmentsByDate should have today's date with 2 appointments
        // - todayDate should be $today
        $this->assertEquals(3, $result['totalAppointments']);
        $this->assertEquals(2, $result['todaysAppointments']);
        $this->assertEquals(2, $result['totalPatients']);
        $this->assertArrayHasKey($today, $result['appointmentsByDate']);
        $this->assertCount(2, $result['appointmentsByDate'][$today]);
        $this->assertEquals($today, $result['todayDate']);
    }

}
