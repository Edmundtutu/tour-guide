<?php
// Simple routing test file
// Access this at: http://localhost/tour-guide/public/test-routing.php

echo "<h1>Tour Guide Routing Test</h1>";

echo "<h2>Server Information</h2>";
echo "<p><strong>REQUEST_URI:</strong> " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "</p>";
echo "<p><strong>SCRIPT_NAME:</strong> " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "</p>";
echo "<p><strong>REQUEST_METHOD:</strong> " . ($_SERVER['REQUEST_METHOD'] ?? 'Not set') . "</p>";
echo "<p><strong>QUERY_STRING:</strong> " . ($_SERVER['QUERY_STRING'] ?? 'Not set') . "</p>";

echo "<h2>Path Analysis</h2>";
$path = $_SERVER['REQUEST_URI'] ?? '';
if (($pos = strpos($path, '?')) !== false) {
    $path = substr($path, 0, $pos);
}
echo "<p><strong>Clean Path:</strong> " . $path . "</p>";

$basePath = str_replace('/public', '', dirname($_SERVER['SCRIPT_NAME']));
echo "<p><strong>Base Path:</strong> " . $basePath . "</p>";

if ($basePath !== '/' && strpos($path, $basePath) === 0) {
    $path = substr($path, strlen($basePath));
}
echo "<p><strong>After Base Path Removal:</strong> " . $path . "</p>";

if (strpos($path, '/public/') === 0) {
    $path = substr($path, 8);
}
echo "<p><strong>After /public/ Removal:</strong> " . $path . "</p>";

if (empty($path) || $path[0] !== '/') {
    $path = '/' . $path;
}
echo "<p><strong>Final Path:</strong> " . $path . "</p>";

echo "<h2>Test Links</h2>";
echo "<p><a href='/'>Home</a></p>";
echo "<p><a href='/hotels'>Hotels</a></p>";
echo "<p><a href='/destinations'>Destinations</a></p>";
echo "<p><a href='/login'>Login</a></p>";
echo "<p><a href='/register'>Register</a></p>";

echo "<h2>Debug Routes</h2>";
echo "<p><a href='/?debug=routes'>Debug Routes</a></p>";
echo "<p><a href='/?debug=true'>Debug Error</a></p>";

echo "<h2>Static Assets Test</h2>";
echo "<p><a href='/assets/css/style.css'>CSS File</a></p>";
echo "<p><a href='/assets/js/main.js'>JS File</a></p>";
?>
