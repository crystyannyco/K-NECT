<!-- 
    EXAMPLE: Add this to the <head> section of your header templates
    Location: app/Views/K-NECT/*/template/header.php
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSRF Token Meta Tag for AJAX Requests -->
    <?= csrf_meta() ?>
    
    <!-- Alternative: JavaScript CSRF Object -->
    <?= csrf_token_js() ?>
    
    <title><?= $page_title ?? 'K-NECT' ?></title>
    
    <!-- Your existing CSS and other meta tags -->
</head>
<body>
    <!-- Your content -->
</body>
</html>
