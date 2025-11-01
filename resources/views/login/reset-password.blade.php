<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
</head>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f2f2f2;
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        margin: 0;
    }

    .container {
        background-color: white;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        width: 100%;
        max-width: 300px;
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
    }

    input[type="email"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    .psw a {
        color: #0078d7;
        text-decoration: none;
        padding: 2px;
    }

    .psw a:hover {
        text-decoration: underline;
    }

    .container h2 {
        text-align: center;
        color: #0078d7;
    }

    .psw {
        text-align: center;
        margin-top: 15px;
    }

    button {
        background-color: #fff;
        color: #000;
        padding: 12px 20px;
        border: 1px solid #4a16d0;
        border-radius: 4px;
        cursor: pointer;
        width: 100%;
        font-size: 16px;
    }

    button:hover {
        background-color: #0078d7;
        color: #fff;
    }

    .error-list {
        color: red;
        margin-bottom: 10px;
    }
</style>

<body>
    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        <div class="container">
            <h2>Reset Password</h2>
            @if ($errors->any())
                <div class="error-list">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <input type="hidden" name="token" value="{{ $token }}">
            <label for="email">Email Address</label>
            <input type="email" name="email" value="{{ request('email') }}" required>

            <label for="password">New Password</label>
            <input type="password" name="password" required>

            <label for="password_confirmation">Confirm Password</label>
            <input type="password" name="password_confirmation" required>

            <button type="submit">Reset Password</button>
            <div class="psw">
                <p><a href="{{ route('login') }}">Back to Login</a></p>
            </div>
        </div>
    </form>
</body>

</html>