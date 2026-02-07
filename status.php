<?php
/**
 * Simple Info Page
 * Test if Apache can execute PHP
 */
?>
<!DOCTYPE html>
<html>
<head>
    <title>Simple Blog - Status</title>
    <style>
        body { font-family: Arial; margin: 40px; }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h1>Simple Blog System - Status Check</h1>
    
    <p><strong>PHP is working!</strong> ✓</p>
    
    <p>If you see this page, PHP execution is working in XAMPP.</p>
    
    <h2>Next Steps:</h2>
    <ol>
        <li>Check if mod_rewrite is enabled in Apache</li>
        <li>Visit <a href="http://localhost/simple-blog-system/">http://localhost/simple-blog-system/</a></li>
        <li>If you still see a directory listing, mod_rewrite is disabled</li>
    </ol>
    
    <h2>To Enable mod_rewrite:</h2>
    <ol>
        <li>Open: <code>c:\xampp\apache\conf\httpd.conf</code></li>
        <li>Find the line: <code>#LoadModule rewrite_module modules/mod_rewrite.so</code></li>
        <li>Remove the <code>#</code> to uncomment it</li>
        <li>Restart Apache in XAMPP Control Panel</li>
        <li>Refresh your browser</li>
    </ol>
    
    <hr>
    <p><a href="http://localhost/simple-blog-system/">Back to Application</a></p>
</body>
</html>
