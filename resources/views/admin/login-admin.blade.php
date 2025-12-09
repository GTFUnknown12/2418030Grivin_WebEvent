<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - CwnXtech</title>
    <link rel="stylesheet" href="{{ asset('css/admin-login.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="admin-login-container">
        <div class="admin-login-box">
            <div class="login-header">
                <div class="logo">
                    <i class="fas fa-crown"></i>
                    <h1>CwnXtech Admin</h1>
                </div>
                <p>Administrator Panel Access</p>
            </div>

            <form action="{{ route('admin.login') }}" method="POST" class="login-form">
                @csrf
                
                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user-shield"></i> Admin Username
                    </label>
                    <input type="text" id="username" name="username" 
                           placeholder="Enter admin username" required
                           value="{{ old('username') }}">
                    @error('username')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-key"></i> Password
                    </label>
                    <input type="password" id="password" name="password" 
                           placeholder="Enter your password" required>
                    @error('password')
                        <span class="error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="login-btn">
                    <i class="fas fa-sign-in-alt"></i> Login to Admin Panel
                </button>

                <div class="login-footer">
                    <a href="{{ route('login') }}" class="back-link">
                        <i class="fas fa-arrow-left"></i> Back to User Login
                    </a>
                    <a href="#" class="forgot-link">Forgot Password?</a>
                </div>
            </form>

            <div class="security-notice">
                <i class="fas fa-shield-alt"></i>
                <p>Restricted Access. Authorized Personnel Only.</p>
            </div>
        </div>
    </div>

    <script>
        // Add password visibility toggle
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleBtn = document.createElement('button');
            toggleBtn.type = 'button';
            toggleBtn.className = 'password-toggle';
            toggleBtn.innerHTML = '<i class="fas fa-eye"></i>';
            
            passwordInput.parentNode.appendChild(toggleBtn);
            
            toggleBtn.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.innerHTML = type === 'password' ? '<i class="fas fa-eye"></i>' : '<i class="fas fa-eye-slash"></i>';
            });
        });
    </script>
</body>
</html>