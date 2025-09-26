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
                $email = $_POST['email'] ?? '';
                $password = $_POST['password'] ?? '';
                
                $user = $this->userService->login($email, $password);
                
                // Redirect based on role
                $this->redirectAfterLogin($user['role']);
            } catch (Exception $e) {
                $this->renderLogin(['error' => $e->getMessage()]);
            }
        } else {
            $this->renderLogin();
        }
    }
    
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            try {
                $data = [
                    'name' => $_POST['name'] ?? '',
                    'email' => $_POST['email'] ?? '',
                    'password' => $_POST['password'] ?? '',
                    'role' => $_POST['role'] ?? 'tourist',
                    'phone' => $_POST['phone'] ?? ''
                ];
                
                $user = $this->userService->register($data);
                
                // Redirect based on role
                $this->redirectAfterLogin($user['role']);
            } catch (Exception $e) {
                $this->renderRegister(['error' => $e->getMessage()]);
            }
        } else {
            $this->renderRegister();
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
    
    private function renderLogin($data = []) {
        // For now, simple output - will be replaced with proper views
        echo "<h1>Login</h1>";
        if (isset($data['error'])) {
            echo "<p style='color: red;'>{$data['error']}</p>";
        }
        echo "<form method='POST'>
            <div>
                <label>Email:</label>
                <input type='email' name='email' required>
            </div>
            <div>
                <label>Password:</label>
                <input type='password' name='password' required>
            </div>
            <button type='submit'>Login</button>
        </form>";
        echo "<a href='" . BASE_URL . "/register'>Register</a>";
    }
    
    private function renderRegister($data = []) {
        echo "<h1>Register</h1>";
        if (isset($data['error'])) {
            echo "<p style='color: red;'>{$data['error']}</p>";
        }
        echo "<form method='POST'>
            <div>
                <label>Name:</label>
                <input type='text' name='name' required>
            </div>
            <div>
                <label>Email:</label>
                <input type='email' name='email' required>
            </div>
            <div>
                <label>Password:</label>
                <input type='password' name='password' required>
            </div>
            <div>
                <label>Phone:</label>
                <input type='text' name='phone'>
            </div>
            <div>
                <label>Role:</label>
                <select name='role'>
                    <option value='tourist'>Tourist</option>
                    <option value='host'>Host</option>
                </select>
            </div>
            <button type='submit'>Register</button>
        </form>";
        echo "<a href='" . BASE_URL . "/login'>Login</a>";
    }
}