<?php
namespace Asus\Medical\Tests\Controllers;
require_once __DIR__ . '/../app/config/config.php';

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;
use Asus\Medical\Controllers\Admin;
use Asus\Medical\Interfaces\AdminServiceInterface;
use ReflectionClass;

// ---- Test doubles & shims (must be defined before including Admin.php) ----

// Minimal base Controller with a testable `view`.
if (!class_exists('Controller')) {
    class Controller {
        public string $lastView = '';
        public array $lastData = [];
        protected function view($tpl, $data = []) { $this->lastView = $tpl; $this->lastData = $data; }
        public function viewPublic($tpl, $data = []) { $this->view($tpl, $data); }
    }
}

// No-op middleware
if (!class_exists('AuthMiddleware')) {
    class AuthMiddleware { public static function adminOnly() {} }
}

// Capture messages and redirects
$GLOBALS['__messages'] = [];
$GLOBALS['__redirect'] = null;

if (!function_exists('setMessage')) {
    function setMessage($type, $msg) { $GLOBALS['__messages'][] = [$type, $msg]; }
}
if (!function_exists('redirect')) {
    function redirect($to) { $GLOBALS['__redirect'] = $to; }
}

// --------------------------------------------------------------------------



final class AdminControllerTest extends TestCase
{
    /** @var AdminServiceInterface&MockObject */
    private $service;

    protected function setUp(): void
    {
        $GLOBALS['__messages'] = [];
        $GLOBALS['__redirect'] = null;
        $this->service = $this->createMock(AdminServiceInterface::class);
    }

    public function testAddDoctorGetRendersForm(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $ctrl = new Admin($this->service);
        $ctrl->adddoctor();

        $ref = new ReflectionClass($ctrl);
        $propView = $ref->getParentClass()->getProperty('lastView');
        $propView->setAccessible(true);
        $lastView = $propView->getValue($ctrl);

        $this->assertSame('admin/adddoctor', $lastView);
        $this->assertNull($GLOBALS['__redirect']);
    }

    public function testAddDoctorPostSuccess(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = [
            'name' => 'Dr. Alice', 'email' => 'alice@example.com', 'phone' => '09...',
            'gender' => 'female', 'password' => 'secret', 'degree' => 'MBBS',
            'experience' => '5', 'bio' => '', 'fee' => '50000', 'specialty' => 'Cardio',
            'address' => 'Yangon', 'start_time' => '09:00', 'end_time' => '17:00',
        ];
        $_FILES = ['image' => ['tmp_name' => '', 'error' => UPLOAD_ERR_NO_FILE]];

        $this->service->expects($this->once())->method('addDoctor')->willReturn(77);

        $ctrl = new Admin($this->service);
        $ctrl->adddoctor();

        $this->assertSame('admin/doctorlist', $GLOBALS['__redirect']);
        $this->assertSame(['success', 'Doctor added successfully! (ID: 77)'], end($GLOBALS['__messages']));
    }

    public function testAddDoctorPostFailureShowsError(): void
    {
        $_SERVER['REQUEST_METHOD'] = 'POST';
        $_POST = []; $_FILES = [];

        $this->service->expects($this->once())
            ->method('addDoctor')
            ->willThrowException(new \Exception('Some validation failed'));

        $ctrl = new Admin($this->service);
        $ctrl->adddoctor();

        $this->assertSame('admin/adddoctor', $GLOBALS['__redirect']);
        $this->assertSame(['error', 'Some validation failed'], end($GLOBALS['__messages']));
    }

    public function testDoctorListPassesDataToView(): void
    {
        $doctors = [['id' => 1, 'name' => 'A'], ['id' => 2, 'name' => 'B']];
        $this->service->method('getAllDoctors')->willReturn($doctors);

        $ctrl = new Admin($this->service);
        $ctrl->doctorlist();

        $ref = new ReflectionClass($ctrl);
        $propData = $ref->getParentClass()->getProperty('lastData');
        $propData->setAccessible(true);

        $this->assertSame(['doctors' => $doctors], $propData->getValue($ctrl));
    }
}
