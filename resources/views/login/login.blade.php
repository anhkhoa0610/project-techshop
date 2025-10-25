<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
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
        width: 100%;
        width: 340px;
        /* max-width: 400px; */
    }

    label {
        font-weight: bold;
        display: block;
        margin-bottom: 8px;
    }

    input[type="text"],
    input[type="password"] {
        width: 100%;
        padding: 10px;
        margin-bottom: 15px;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-sizing: border-box;
    }

    input[type="checkbox"] {
        margin-right: 5px;
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

    .container-2 {
        padding: 5px;
    }

    .container h2 {
        text-align: center;
        color: #0078d7;
    }

    .container-3 {
        margin-top: 10px;
    }

    .container-3 .psw1 {
        margin-right: 63px;
    }

    .psw1 a:hover {
        background-color: #0078d7;
        color: #fff;
        text-decoration: none;
    }

    .psw2 a:hover {
        background-color: #0078d7;
        color: #fff;
        text-decoration: none;
    }

    .psw {
        text-align: center;
        margin-top: 25px;
    }
</style>

<body>
    <form action="{{ route('user.authUser') }}" method="post">
        @csrf
        <div class="container">
            <h2>Sign In</h2>
            <div class="mb-3">
                <label for="email">Email</label>
                <input type="text" placeholder="Email" id="email" class="form-control" name="email" required autofocus>
                <!-- @error('email')
                    <div class="alert alert-danger mt-1">{{ $message }}</div>
                @enderror -->
                @if ($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
            </div>

            <div class="mb-3">

                <label for="password">Password</label>
                <input type="password" placeholder="Password" id="password" class="form-control" name="password"
                    required>
                @if ($errors->has('password'))
                    <span class="text-danger">{{ $errors->first('password') }}</span>
                @endif
            </div>
            @if ($errors->has('login'))
                <div class="alert alert-danger">
                    {{ $errors->first('login') }}
                </div>
            @endif

            <div class="container-2">
                <span class="psw"> <a href="{{ route('forgot.form') }}">Forgot password?</a></span>
            </div>
            <button type="submit">Sign In</button>
            <div class="container-3">
                <span class="psw psw1"> <a href="{{ route('reset.form') }}">Reset password</a></span>
                <span class="psw psw2"> <a href="{{ route('register') }}">Create account</a></span>
            </div>
            <div class="psw">
                <p><a href="{{ route('index') }}">Back to index <i class="fas fa-arrow-right"></i></a></p>
            </div>
        </div>
    </form>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>