<?php
require_once __DIR__ . '/../services/UserService.php';

class AuthController {
    private $userService;
    
    public function __construct() {
        $this->userService = new UserService();
    }
    
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    throw new Exception("Invalid request");
                }
                
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                $user = $this->userService->login($email, $password);
                
                // Set success message
                $_SESSION['success'] = 'Welcome back, ' . $user['name'] . '!';
                
                // Redirect based on role
                $this->redirectAfterLogin($user['role']);
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . BASE_URL . '/login');
                exit;
            }
        } else {
            $data = [
                'title' => 'Login'
            ];
            echo View::renderWithLayout('auth/login', $data);
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                // Validate CSRF token
                if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
                    throw new Exception("Invalid request");
                }
                
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'password' => $_POST['password'] ?? '',
                    'role' => $_POST['role'] ?? 'tourist',
                    'phone' => $_POST['phone'] ?? '',
                    'hotel_name' => $_POST['hotel_name'] ?? '',
                    'hotel_location' => $_POST['hotel_location'] ?? '',
                    'hotel_description' => $_POST['hotel_description'] ?? ''
                ];
                
                $user = $this->userService->register($data);
                
                // Set success message
                $_SESSION['success'] = 'Account created successfully! Welcome, ' . $user['name'] . '!';
                
                // Redirect based on role
                $this->redirectAfterLogin($user['role']);
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
                $_SESSION['old_input'] = $_POST;
                header('Location: ' . BASE_URL . '/register');
                exit;
            }
        } else {
            $data = [
                'title' => 'Register'
            ];
            echo View::renderWithLayout('auth/register', $data);
        }
    }
    
    public function logout() {
        $this->userService->logout();
        header('Location: ' . BASE_URL . '/login');
        exit;
    }
    
    private function redirectAfterLogin($role) {
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . '/admin/dashboard');
                break;
            case 'host':
                header('Location: ' . BASE_URL . '/host/dashboard');
                break;
            default:
                header('Location: ' . BASE_URL . '/');
                break;
        }
        exit;
    }
    
}