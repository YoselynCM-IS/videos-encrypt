<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Majestic Education</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Fa fa icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <style>
        #logo {
            font-size: 16px;
            font-weight: 600;
            color:#a9343A;
        }
    </style>
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="#" id="logo">
                    MAJESTIC EDUCATION
                </a>
            </div>
        </nav>

        <main class="py-4 container">
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-danger mb-3" data-bs-toggle="modal" data-bs-target="#exampleModal">
                Crear accesos
            </button>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>N</th>
                        <th>Contraseña</th>
                        <th>Expira el</th>
                        <th>Video</th>
                        <th>Creado el</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($accesos as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>
                                <span id="pass-{{ $item->id }}">
                                    {{ $item->view_password }}
                                </span>
                            </td>
                            <td>{{ $item->expires_at }}</td>
                            <td>{{ $item->video_path ?? '—' }}</td>
                            <td>{{ $item->created_at }}</td>
                            <td class="d-flex gap-2">
                                <!-- COPIAR -->
                                <button 
                                    class="btn btn-sm btn-dark"
                                    onclick="copyPassword('{{ $item->id }}')">
                                    Copiar
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Modal -->
            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Crear accesos</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                     <div class="card-body">
                        <form action="{{ route('accesos.store') }}" method="POST">
                            @csrf
                            <!-- Número de accesos -->
                            <div class="mb-3">
                                <label for="numero_accesos" class="form-label">Número de accesos</label>
                                <input 
                                    type="number" 
                                    name="numero_accesos" 
                                    id="numero_accesos" 
                                    class="form-control" 
                                    min="1" 
                                    required>
                            </div>
                            <!-- Fecha de expiración -->
                            <div class="mb-3">
                                <label for="fecha_expiracion" class="form-label">Fecha de expiración</label>
                                <input 
                                    type="date" 
                                    name="fecha_expiracion" 
                                    id="fecha_expiracion" 
                                    class="form-control" 
                                    required>
                            </div>
                            <!-- Botón guardar -->
                            <div class="d-grid">
                                <button type="submit" class="btn btn-success">
                                    Guardar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </main>
    </div>
    <!-- JS copiar -->
    <script>
        function copyPassword(id) {
            let text = document.getElementById('pass-' + id).innerText;

            navigator.clipboard.writeText(text).then(() => {
                alert('Contraseña copiada');
            });
        }
    </script>
    <!-- SWAM -->
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <!-- BOOTSTRAP -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <!-- DROPBOX -->
    <script type="text/javascript" src="https://www.dropbox.com/static/api/2/dropins.js" id="dropboxjs" data-app-key="u7hg1jo5ch9u7of"></script>
</body>
</html>
