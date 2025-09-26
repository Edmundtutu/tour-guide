<?php
require_once __DIR__ . '/../core/BaseModel.php';

class User extends BaseModel {
    protected $table = 'users';
    
    public function findByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return $stmt->fetch();
    }
    
    public function findByRole($role) {
        return $this->findAll(['role' => $role]);
    }
    
    public function createUser($data) {
        // Validate required fields
        $required = ['name', 'email', 'password'];
        foreach ($required as $field) {
            if (empty($data[$field])) {
                throw new Exception("Field {$field} is required");
            }
        }
        
        // Check if email already exists
        if ($this->findByEmail($data['email'])) {
            throw new Exception("Email already exists");
        }
        
        // Hash password
        $data['password'] = Auth::hashPassword($data['password']);
        
        // Set default role if not provided
        if (!isset($data['role'])) {
            $data['role'] = 'tourist';
        }
        
        return $this->create($data);
    }
    
    public function updateProfile($userId, $data) {
        // Remove sensitive fields from update
        unset($data['password'], $data['role'], $data['id']);
        
        return $this->update($userId, $data);
    }
    
    public function changePassword($userId, $newPassword) {
        $hashedPassword = Auth::hashPassword($newPassword);
        return $this->update($userId, ['password' => $hashedPassword]);
    }
}