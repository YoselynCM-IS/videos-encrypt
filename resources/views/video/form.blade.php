<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MAJESTIC EDUCATION</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body>
    <div class="card">
        <h2>MAJESTIC EDUCATION</h2>

        <form method="POST" action="{{ route('video.validate') }}">
            @csrf
            <!-- <div class="input-group">
                <i class="fa-solid fa-user"></i>
                <input name="name" placeholder="Nombre completo" required>
            </div> -->

            <div class="input-group">
                <i class="fa-solid fa-lock"></i>
                <input type="password" name="password" placeholder="Contraseña" required>
                @if(session('error'))
                    <label style="color:#f8f037">{{ session('error') }}</label>
                @endif
            </div>

            <button type="submit" class="btn">Acceder</button>
        </form>
    </div>
</body>
</html>
