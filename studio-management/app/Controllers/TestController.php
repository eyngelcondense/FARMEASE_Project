<?php

namespace App\Controllers;

class TestController extends \CodeIgniter\Controller
{
    public function index()
    {
        echo "<h1>Studio Management Test</h1>";
        echo "<p>If you can see this, the basic controller is working.</p>";
        
        // Test database connection
        try {
            $db = \Config\Database::connect();
            echo "<p>✅ Database connection: SUCCESS</p>";
        } catch (\Exception $e) {
            echo "<p>❌ Database connection: " . $e->getMessage() . "</p>";
        }
        
        // Test model loading
        try {
            $studioModel = model(\App\Models\StudioModel::class);
            echo "<p>✅ Studio model: LOADED</p>";
        } catch (\Exception $e) {
            echo "<p>❌ Studio model: " . $e->getMessage() . "</p>";
        }
        
        // Test session
        if (session_status() === PHP_SESSION_ACTIVE) {
            echo "<p>✅ Session: ACTIVE</p>";
        } else {
            echo "<p>❌ Session: INACTIVE</p>";
        }
        
        echo "<hr>";
        echo "<h3>Environment Info:</h3>";
        echo "<p>PHP Version: " . PHP_VERSION . "</p>";
        echo "<p>CodeIgniter Version: " . \CodeIgniter\CodeIgniter::CI_VERSION . "</p>";
        echo "<p>Base URL: " . base_url() . "</p>";
        echo "<p>Writable Path: " . WRITEPATH . "</p>";
        
        // Check writable directories
        $writableDirs = ['session', 'cache', 'logs', 'uploads'];
        foreach ($writableDirs as $dir) {
            $path = WRITEPATH . $dir;
            if (is_dir($path) && is_writable($path)) {
                echo "<p>✅ $dir: WRITABLE</p>";
            } else {
                echo "<p>❌ $dir: NOT WRITABLE</p>";
            }
        }
    }
}
