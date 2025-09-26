<?php

class Auth {
    
    public static function startSession() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    
    public static function login($user) {
        self::startSession();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];
        $_SESSION['login_time'] = time();
        
        return true;
    }
    
    public static function logout() {
        self::startSession();
        session_destroy();
        return true;
    }
    
    public static function isLoggedIn() {
        self::startSession();
        
        if (!isset($_SESSION['user_id'])) {
            return false;
        }
        
        // Check session timeout
        if (isset($_SESSION['login_time']) && 
            (time() - $_SESSION['login_time']) > SESSION_TIMEOUT) {
            self::logout();
            return false;
        }
        
        return true;
    }
    
    public static function getCurrentUser() {
        if (!self::isLoggedIn()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['user_id'],
            'name' => $_SESSION['user_name'],
            'email' => $_SESSION['user_email'],
            'role' => $_SESSION['user_role']
        ];
    }
    
    public static function hasRole($requiredRole) {
        $user = self::getCurrentUser();
        if (!$user) {
            return false;
        }
        
        return $user['role'] === $requiredRole;
    }
    
    public static function requireRole($requiredRole) {
        if (!self::hasRole($requiredRole)) {
            throw new Exception("Access denied. Required role: {$requiredRole}");
        }
    }
    
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            throw new Exception("Authentication required");
        }
    }
    
    public static function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }
    
    public static function verifyPassword($password, $hash) {
        return password_verify($password, $hash);
    }
}
?>