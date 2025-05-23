<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Register for PropertyApp - Join as a buyer or agent to explore premium real estate opportunities.">
    <title>Register - PropertyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #1e40af;
            --primary-dark: #1e3a8a;
            --secondary: #3b82f6;
            --accent: #10b981;
            --text: #1f2937;
            --bg-light: #f8fafc;
            --error: #ef4444;
        }

        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            overflow: auto;
        }

        .register-container {
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
            max-width: 480px;
            width: 100%;
            margin: 2rem;
            animation: slideUp 0.8s ease-out;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, var(--secondary), var(--primary));
        }

        .register-container h2 {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--text);
            text-align: center;
            margin-bottom: 0.5rem;
        }

        .register-container p.subtitle {
            font-size: 0.95rem;
            color: #6b7280;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 500;
            color: var(--text);
            margin-bottom: 0.5rem;
        }

        .form-control, .form-select {
            border-radius: 8px;
            border: 1px solid #d1d5db;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: border-color 0.2s ease, box-shadow 0.2s ease;
            background: #fff;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--secondary);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
            outline: none;
        }

        .form-control.is-invalid {
            border-color: var(--error);
            background: #fef2f2;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            color: var(--error);
        }

        .btn-primary {
            background: linear-gradient(90deg, var(--secondary), var(--primary));
            border: none;
            border-radius: 8px;
            padding: 0.85rem;
            font-size: 1rem;
            font-weight: 500;
            width: 100%;
            transition: transform 0.2s ease, opacity 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            opacity: 0.95;
        }

        .btn-primary:active {
            transform: translateY(0);
        }

        .error-alert {
            background: #fef2f2;
            border: 1px solid var(--error);
            border-radius: 8px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
            color: var(--error);
            text-align: center;
            animation: shake 0.3s ease;
        }

        .login-link {
            text-align: center;
            margin-top: 1.5rem;
            font-size: 0.9rem;
        }

        .login-link a {
            color: var(--secondary);
            font-weight: 500;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .login-link a:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }

        .input-group-text {
            background: #f1f5f9;
            border: 1px solid #d1d5db;
            border-radius: 8px 0 0 8px;
            color: #6b7280;
        }

        .password-toggle {
            cursor: pointer;
            user-select: none;
            background: #f1f5f9;
            border: 1px solid #d1d5db;
            border-left: none;
            border-radius: 0 8px 8px 0;
            padding: 0.75rem;
            color: #6b7280;
            transition: background 0.2s ease;
        }

        .password-toggle:hover {
            background: #e5e7eb;
        }

        @keyframes slideUp {
            0% { opacity: 0; transform: translateY(20px); }
            100% { opacity: 1; transform: translateY(0); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @media (max-width: 576px) {
            .register-container {
                padding: 1.5rem;
                margin: 1rem;
            }

            .register-container h2 {
                font-size: 1.5rem;
            }

            .register-container p.subtitle {
                font-size: 0.85rem;
            }

            .btn-primary {
                font-size: 0.9rem;
                padding: 0.75rem;
            }
        }
    </style>
</head>
<body>
    <div class="register-container" role="main" aria-labelledby="register-title">
        <h2 id="register-title">Create Your Account</h2>
        <p class="subtitle">Join PropertyApp as a buyer or agent today</p>

        @if ($errors->any())
            <div class="error-alert" role="alert" aria-live="assertive">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}" id="register-form" novalidate>
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                           value="{{ old('name') }}" required aria-describedby="name-error">
                </div>
                @error('name')
                    <div id="name-error" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email Address</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                    <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" 
                           value="{{ old('email') }}" required aria-describedby="email-error">
                </div>
                @error('email')
                    <div id="email-error" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" id="password" 
                           class="form-control @error('password') is-invalid @enderror" required 
                           aria-describedby="password-error">
                    <span class="password-toggle" onclick="togglePassword('password')" aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
                @error('password')
                    <div id="password-error" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="password_confirmation" class="form-label">Confirm Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password_confirmation" id="password_confirmation" 
                           class="form-control" required aria-describedby="password_confirmation-error">
                    <span class="password-toggle" onclick="togglePassword('password_confirmation')" 
                          aria-label="Toggle password visibility">
                        <i class="fas fa-eye"></i>
                    </span>
                </div>
            </div>

            <div class="mb-4">
                <label for="role" class="form-label">Account Type</label>
                <select name="role" id="role" class="form-select @error('role') is-invalid @enderror" 
                        required aria-describedby="role-error">
                    <option value="" disabled selected>Select account type</option>
                    <option value="buyer" {{ old('role') == 'buyer' ? 'selected' : '' }}>Buyer</option>
                    <option value="agent" {{ old('role') == 'agent' ? 'selected' : '' }}>Agent</option>
                </select>
                @error('role')
                    <div id="role-error" class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Register Now</button>
        </form>

        <div class="login-link">
            <p>Already have an account? <a href="{{ route('login') }}">Sign in here</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Password toggle functionality
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Real-time form validation
        const form = document.getElementById('register-form');
        const inputs = form.querySelectorAll('input, select');

        inputs.forEach(input => {
            input.addEventListener('input', () => {
                if (input.checkValidity()) {
                    input.classList.remove('is-invalid');
                    input.classList.add('is-valid');
                } else {
                    input.classList.remove('is-valid');
                    input.classList.add('is-invalid');
                }
            });
        });

        // Prevent form submission if invalid
        form.addEventListener('submit', (e) => {
            if (!form.checkValidity()) {
                e.preventDefault();
                inputs.forEach(input => {
                    if (!input.checkValidity()) {
                        input.classList.add('is-invalid');
                    }
                });
            }
        });
    </script>
</body>
</html>