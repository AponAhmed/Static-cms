<!DOCTYPE html>
<html>

<head>
    <title>Login Page</title>
    <link rel="stylesheet" type="text/css" href="<?php echo admin_assets('login.css') ?>">
</head>

<body>
    <div class="login-container">
        <div class="login-card">
            <div class="login-body">
                <h2>Admin Login</h2>
                <?php echo isset($error) ? $error : "" ?>
                <form method="POST">
                    <div class="form__group field">
                        <input type="text" class="form__field" placeholder="User Name" name="username" id='email' required autocomplete="email" autofocus>
                        <label for="email" class="form__label">User Name</label>
                    </div>
                    <div class="form__group field">
                        <input type="password" class="form__field" placeholder="Password" name="password" id='password' required autocomplete="current-password">
                        <label for="password" class="form__label">Password</label>
                    </div><br>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Login</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>