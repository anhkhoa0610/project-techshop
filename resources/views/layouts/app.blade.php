<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Product Management')</title>
    

    @livewireStyles
    @powerGridStyles
</head>
<body class="bg-gray-100">
    @yield('content')

    @livewireScripts
    @powerGridScripts
</body>
</html>