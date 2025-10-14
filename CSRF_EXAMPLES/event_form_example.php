<!-- 
    EXAMPLE: Event Creation Form with CSRF Protection
    Location: app/Views/K-NECT/events/create.php
-->

<div class="event-create-container">
    <h2>Create New Event</h2>
    
    <form action="<?= base_url('events/store') ?>" method="post" enctype="multipart/form-data" id="eventForm">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="title">Event Title</label>
            <input type="text" id="title" name="title" required>
        </div>
        
        <div class="form-group">
            <label for="description">Description</label>
            <textarea id="description" name="description" rows="5"></textarea>
        </div>
        
        <div class="form-group">
            <label for="category">Category</label>
            <select id="category" name="category" required>
                <option value="">Select Category</option>
                <option value="health">Health</option>
                <option value="education">Education</option>
                <option value="governance">Governance</option>
            </select>
        </div>
        
        <div class="form-row">
            <div class="form-group">
                <label for="start_datetime">Start Date & Time</label>
                <input type="datetime-local" id="start_datetime" name="start_datetime" required>
            </div>
            
            <div class="form-group">
                <label for="end_datetime">End Date & Time</label>
                <input type="datetime-local" id="end_datetime" name="end_datetime" required>
            </div>
        </div>
        
        <div class="form-group">
            <label for="location">Location</label>
            <input type="text" id="location" name="location" required>
        </div>
        
        <div class="form-group">
            <label for="event_banner">Event Banner</label>
            <input type="file" id="event_banner" name="event_banner" accept="image/*">
        </div>
        
        <div class="form-group">
            <label for="target_participants">Target Participants</label>
            <input type="number" id="target_participants" name="target_participants" min="1">
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Event</button>
            <a href="<?= base_url('events') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
