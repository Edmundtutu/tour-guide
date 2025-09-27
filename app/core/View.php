<?php

class View {
    private static $data = [];
    
    public static function render($template, $data = []) {
        self::$data = array_merge(self::$data, $data);
        
        // Extract data to variables for use in templates
        extract(self::$data);
        
        // Start output buffering
        ob_start();
        
        // Include the template
        $templatePath = __DIR__ . '/../../views/' . $template . '.php';
        if (!file_exists($templatePath)) {
            throw new Exception("Template not found: {$template}");
        }
        
        include $templatePath;
        
        // Get the content and clean the buffer
        $content = ob_get_clean();
        
        return $content;
    }
    
    public static function renderWithLayout($template, $data = [], $layout = 'main') {
        // Render the main content
        $content = self::render($template, $data);
        
        // Add content to data for layout
        $data['content'] = $content;
        
        // Render with layout
        return self::render("layouts/{$layout}", $data);
    }
    
    public static function partial($template, $data = []) {
        return self::render($template, $data);
    }
    
    public static function setData($key, $value) {
        self::$data[$key] = $value;
    }
    
    public static function getData($key = null) {
        if ($key === null) {
            return self::$data;
        }
        return isset(self::$data[$key]) ? self::$data[$key] : null;
    }
    
    // Helper methods for common template needs
    public static function url($path = '') {
        return BASE_URL . $path;
    }
    
    public static function asset($path) {
        return BASE_URL . '/assets/' . $path;
    }
    
    public static function csrfToken() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public static function old($key, $default = '') {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return $_SESSION['old_input'][$key] ?? $default;
    }
    
    public static function errors() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['errors']) ? $_SESSION['errors'] : [];
    }
    
    public static function success() {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        return isset($_SESSION['success']) ? $_SESSION['success'] : null;
    }
    
    public static function flash($key) {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (isset($_SESSION[$key])) {
            $value = $_SESSION[$key];
            unset($_SESSION[$key]);
            return $value;
        }
        return null;
    }
}
