<?php
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Auth.php';

class UserService {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new User();
    }
    
    public function register($data) {
        // Validate input
        if (strlen($data['password']) < PASSWORD_MIN_LENGTH) {
            throw new Exception("Password must be at least " . PASSWORD_MIN_LENGTH . " characters long");
        }
        
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format");
        }
        
        try {
            $userId = $this->userModel->createUser($data);
            
            // Auto-login after registration
            $user = $this->userModel->findById($userId);
            Auth::login($user);
            
            return $user;
        } catch (Exception $e) {
            throw new Exception("Registration failed: " . $e->getMessage());
        }
    }
    
    public function login($email, $password) {
        if (empty($email) || empty($password)) {
            throw new Exception("Email and password are required");
        }
        
        $user = $this->userModel->findByEmail($email);
        if (!$user) {
            throw new Exception("Invalid email or password");
        }
        
        if (!Auth::verifyPassword($password, $user['password'])) {
            throw new Exception("Invalid email or password");
        }
        
        Auth::login($user);
        return $user;
    }
    
    public function logout() {
        return Auth::logout();
    }
    
    public function getCurrentUser() {
        return Auth::getCurrentUser();
    }
    
    public function updateProfile($userId, $data) {
        Auth::requireLogin();
        
        // Users can only update their own profile unless they're admin
        $currentUser = Auth::getCurrentUser();
        if ($currentUser['id'] != $userId && $currentUser['role'] !== 'admin') {
            throw new Exception("Access denied");
        }
        
        return $this->userModel->updateProfile($userId, $data);
    }
    
    public function changePassword($userId, $currentPassword, $newPassword) {
        Auth::requireLogin();
        
        $currentUser = Auth::getCurrentUser();
        if ($currentUser['id'] != $userId) {
            throw new Exception("Access denied");
        }
        
        // Verify current password
        $user = $this->userModel->findById($userId);
        if (!Auth::verifyPassword($currentPassword, $user['password'])) {
            throw new Exception("Current password is incorrect");
        }
        
        if (strlen($newPassword) < PASSWORD_MIN_LENGTH) {
            throw new Exception("New password must be at least " . PASSWORD_MIN_LENGTH . " characters long");
        }
        
        return $this->userModel->changePassword($userId, $newPassword);
    }
    
    public function getAllUsers() {
        Auth::requireRole('admin');
        return $this->userModel->findAll();
    }
    
    public function getUsersByRole($role) {
        Auth::requireRole('admin');
        return $this->userModel->findByRole($role);
    }
}
