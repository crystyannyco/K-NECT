<!-- Main Content Area: embed Bulletin as the dashboard content -->
<div class="flex-1 flex overflow-hidden">
    <div class="flex-1 overflow-auto">
        <?php
            // Render the Bulletin view directly as the dashboard body with provided data
            echo view('K-NECT/KK/Bulletin/index', $bulletinData ?? []);
        ?>
    </div>
</div>
