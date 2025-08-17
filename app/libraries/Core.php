<?php
namespace Asus\Medical\libraries;

use Exception;
use ReflectionClass;

class Core
{
    protected $currentController = 'Pages';
    protected $currentMethod = 'index';
    protected $params = [];

    // Cache of instantiated dependencies
    protected $container = [];

    // Interface to concrete class bindings
    protected $interfaceBindings = [
        'AdminServiceInterface'       => 'AdminService',
        'AppointmentServiceInterface' => 'AppointmentService',
        'DoctorServiceInterface'      => 'DoctorService',
        'PatientServiceInterface'     => 'PatientService',
        'AdminRepositoryInterface'    => 'AdminRepository',
        'AppointmentRepositoryInterface' => 'AppointmentRepository',
        'DoctorRepositoryInterface'   => 'DoctorRepository',
        'PatientRepositoryInterface'  => 'PatientRepository',
        'ImageUploadServiceInterface' => 'ImageUploadService',
    ];

    public function __construct()
    {
        $url = $this->getURL();

        // Controller detection
        if (isset($url[0])) {
            $controllerName = ucwords($url[0]);
            $filePath = "../app/controllers/$controllerName.php";
            if (file_exists($filePath)) {
                $this->currentController = $controllerName;
                unset($url[0]);
            }
        }

        // Instantiate controller safely
        $this->currentController = $this->resolveController($this->currentController);

        // Method detection
        if (isset($url[1]) && method_exists($this->currentController, $url[1])) {
            $this->currentMethod = $url[1];
            unset($url[1]);
        }

        // Parameters
        $this->params = $url ? array_values($url) : [];

        // Call method
        call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
    }

    protected function resolveController(string $controllerName)
    {
        $fullClass = "Asus\\Medical\\Controllers\\$controllerName";

        if (!class_exists($fullClass)) {
            $filePath = "../app/controllers/$controllerName.php";
            if (!file_exists($filePath)) {
                throw new Exception("Controller file '$filePath' not found.");
            }
            require_once $filePath;
        }

        return $this->resolveClass($fullClass);
    }

    protected function resolveDependency(string $className)
    {
        // Convert interface to concrete class if binding exists
        $shortName = (strpos($className, '\\') !== false) 
            ? substr(strrchr($className, "\\"), 1) 
            : $className;

        if (interface_exists($className) && isset($this->interfaceBindings[$shortName])) {
            $shortName = $this->interfaceBindings[$shortName];
            $className = "Asus\\Medical\\" . $this->guessNamespace($shortName) . $shortName;
        }

        if (!class_exists($className)) {
            $filePath = $this->getFilePathForClass($shortName);
            if (!$filePath || !file_exists($filePath)) {
                throw new Exception("Class file for '$className' not found at '$filePath'");
            }
            require_once $filePath;
        }

        return $this->resolveClass($className);
    }

    protected function resolveClass(string $fullClass)
    {
        $reflection = new ReflectionClass($fullClass);
        $constructor = $reflection->getConstructor();

        if (!$constructor || $constructor->getNumberOfParameters() === 0) {
            return new $fullClass;
        }

        $dependencies = [];
        foreach ($constructor->getParameters() as $param) {
            $depClass = $param->getType() && !$param->getType()->isBuiltin()
                ? $param->getType()->getName()
                : null;

            if ($depClass) {
                if (!isset($this->container[$depClass])) {
                    $this->container[$depClass] = $this->resolveDependency($depClass);
                }
                $dependencies[] = $this->container[$depClass];
            } elseif ($param->isDefaultValueAvailable()) {
                $dependencies[] = $param->getDefaultValue();
            } else {
                throw new Exception("Cannot resolve dependency '{$param->getName()}' for class '{$fullClass}'");
            }
        }

        return $reflection->newInstanceArgs($dependencies);
    }

    protected function getFilePathForClass(string $shortName): ?string
    {
        if (stripos($shortName, 'Service') !== false) {
            return "../app/services/$shortName.php";
        }
        if (stripos($shortName, 'Repository') !== false) {
            return "../app/repositories/$shortName.php";
        }
        if (stripos($shortName, 'Controller') !== false) {
            return "../app/controllers/$shortName.php";
        }
        if (stripos($shortName, 'Interface') !== false) {
            return "../app/interfaces/$shortName.php";
        }
        if (stripos($shortName, 'Library') !== false) {
            return "../app/libraries/$shortName.php";
        }
        return "../app/$shortName.php";
    }

    protected function guessNamespace(string $shortName): string
    {
        if (stripos($shortName, 'Service') !== false) return 'Services\\';
        if (stripos($shortName, 'Repository') !== false) return 'Repositories\\';
        if (stripos($shortName, 'Controller') !== false) return 'Controllers\\';
        if (stripos($shortName, 'Interface') !== false) return 'Interfaces\\';
        if (stripos($shortName, 'Library') !== false) return 'Libraries\\';
        if (stripos($shortName, 'Middleware') !== false) return 'Middleware\\'; // <--- add this
        return '';
    }

    public function getURL(): array
    {
        if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
    // public function getURL(): array
    // {
    //     /** @var array $_GET */
    //     $url = $_GET['url'] ?? null;

    //     if ($url) {
    //         $url = rtrim($url, '/');
    //         $url = filter_var($url, FILTER_SANITIZE_URL);
    //         return explode('/', $url);
    //     }

    //     return [];
    // }
//     public function getURL(): array
// {
//     /** @var array $_GET */
//     $url = $_GET['url'] ?? null;

//     // Fallback for CLI or missing GET
//     if (!$url && php_sapi_name() === 'cli') {
//         global $argv;
//         $url = $argv[1] ?? '';
//     }

//     if ($url) {
//         $url = rtrim($url, '/');
//         $url = filter_var($url, FILTER_SANITIZE_URL);
//         return explode('/', $url);
//     }

//     return [];
// }


}
