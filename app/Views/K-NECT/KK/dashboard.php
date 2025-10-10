<?php
    // Render the Bulletin view directly as the dashboard body with provided data
    // Note: Bulletin/index.php already has its own wrapper with ml-64 pt-16
    echo view('K-NECT/KK/Bulletin/index', $bulletinData ?? []);
?>
