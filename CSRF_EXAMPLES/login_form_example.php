<!-- 
    EXAMPLE: Login Form with CSRF Protection
    Location: app/Views/K-NECT/login.php
-->

<div class="login-container">
    <h2>Login to K-NECT</h2>
    
    <!-- Method 1: Using csrf_field() helper (Recommended) -->
    <form action="<?= base_url('loginProcess') ?>" method="post" id="loginForm">
        <?= csrf_field() ?>
        
        <div class="form-group">
            <label for="login">Username or Email</label>
            <input type="text" id="login" name="login" required>
        </div>
        
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Login</button>
    </form>
    
    <!-- Method 2: Using form_open() helper (Alternative) -->
    <?php helper('form'); ?>
    <?= form_open('loginProcess', ['id' => 'loginFormAlt']) ?>
        <!-- CSRF token is automatically included -->
        
        <div class="form-group">
            <label for="login2">Username or Email</label>
            <input type="text" id="login2" name="login" required>
        </div>
        
        <div class="form-group">
            <label for="password2">Password</label>
            <input type="password" id="password2" name="password" required>
        </div>
        
        <button type="submit" class="btn btn-primary">Login</button>
    <?= form_close() ?>
</div>
