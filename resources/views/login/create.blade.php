<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
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
        /* width: 100%; */
        width: 500px;
        margin-top: 20%;
        margin-bottom: 5%;
    }

    label {
        font-weight: bold;
        display: block;
    }

    input[type="text"],
    input[type="password"],
    input[type="email"],
    input[type="tel"],
    input[type="date"] {
        width: 100%;
        padding: 10px;
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

    .container h2 {
        text-align: center;
        color: #0078d7;
        margin: 10px 0 10px 0;
    }

    .psw a {
        color: #0078d7;
        text-decoration: none;
        padding: 2px;
    }

    .psw a:hover {
        text-decoration: underline;
    }

    .psw {
        text-align: center;
        margin-top: 15px;
    }
</style>

<body>
    <form action="{{ route('register.postUser') }}" method="post">
        @csrf
        <div class="container">
            <h2>Create Account</h2>
            <div class="mb-3">
                <label for="fullname"><b>Full Name</b></label>
                <input type="text" placeholder="Enter Full Name" id="full_name" name="full_name" required>
                @error('full_name')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="email"><b>Email</b></label>
                <input type="email" placeholder="Enter Email" id="email_address" name="email" required>
                @error('email')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="phone"><b>Phone</b></label>
                <input type="tel" placeholder="Enter Phone Number" name="phone" required>
                @error('phone')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="address"><b>Address</b></label>
                <input type="text" placeholder="Enter Address" name="address" required>
                @error('address')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="birth"><b>Date of Birth</b></label>
                <input type="date" name="dob" required>
                @error('dob')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="psw"><b>Password</b></label>
                <input type="password" placeholder="Enter Password" id="password" name="password" required>
                @error('password')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>
            <div class="mb-3">
                <label for="psw-repeat"><b>Repeat Password</b></label>
                <input type="password" placeholder="Repeat Password" id="password_confirmation" name="password_confirmation"
                    required>
                @error('password_confirmation')
                    <div class="text-danger mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit">Create Account</button>
            <div class="psw">
                <span>Already have an account? <a href="{{ route('login') }}">Sign In</a></span>
            </div>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>