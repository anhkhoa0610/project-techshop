<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
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
        width: 400px;
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
        /* margin-bottom: 15px; */
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
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
</style>

<body>
    <form action="{{ route('reset') }}" method="post">
        @csrf
        <div class="container">
            <h2>Reset Password</h2>
            <div class="mb-3">
                <label for="email"><b>Email Address</b></label>
                <input type="email" placeholder="Enter your email" name="email" required>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="old-password"><b>Old Password</b></label>
                <input type="password" placeholder="Enter Old Password" name="old_password" required>
                @error('old_password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="new-password"><b>New Password</b></label>
                <input type="password" placeholder="Enter New Password" name="new_password" required>
                @error('new_password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="repeat-password"><b>Repeat New Password</b></label>
                <input type="password" placeholder="Repeat New Password" name="new_password_confirmation" required>
                @error('new_password_confirmation')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">Submit</button>
            <div class="psw">
                <p><a href="{{ route('login') }}">Back to Login</a></p>
            </div>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>