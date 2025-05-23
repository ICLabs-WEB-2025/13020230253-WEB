<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - PropertyApp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.5)), url('https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?ixlib=rb-4.0.3&auto=format&fit=crop&w=1920&q=80');
            background-size: cover;
            background-position: center;
            overflow: hidden;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.95);
            padding: 2.5rem 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            animation: fadeIn 1s ease-in-out;
        }
        .login-container h2 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1e3a8a;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group label {
            font-weight: 500;
            color: #4b5563;
        }
        .form-control {
            border-radius: 12px;
            border: 1px solid #d1d5db;
            padding: 0.85rem;
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
        }
        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 8px rgba(59, 130, 246, 0.3);
            outline: none;
        }
        .btn-login {
            background: linear-gradient(90deg, #3b82f6 0%, #1e3a8a 100%);
            color: #fff;
            border: none;
            border-radius: 12px;
            padding: 0.85rem 1.5rem;
            font-weight: 500;
            width: 100%;
            transition: transform 0.3s ease, opacity 0.3s ease;
        }
        .btn-login:hover {
            transform: translateY(-3px);
            opacity: 0.9;
        }
        .error-list {
            list-style: none;
            padding: 0;
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 0.5rem;
            text-align: center;
        }
        .register-link {
            text-align: center;
            margin-top: 1.5rem;
        }
        .register-link a {
            color: #3b82f6;
            font-weight: 500;
            text-decoration: none;
            transition: color 0.3s ease;
        }
        .register-link a:hover {
            color: #1e3a8a;
            text-decoration: underline;
        }
        @keyframes fadeIn {
            0% { opacity: 0; transform: translateY(30px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @media (max-width: 576px) {
            .login-container {
                padding: 2rem;
                margin: 1rem;
            }
            .login-container h2 {
                font-size: 1.8rem;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login ke PropertyApp</h2>

        @if ($errors->any())
            <div>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <div class="form-group mb-3">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required>
            </div>
            <div class="form-group mb-4">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn-login">Login</button>
        </form>

        <div class="register-link">
            <p>Belum punya akun? <a href="{{ route('register') }}">Register di sini</a></p>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>