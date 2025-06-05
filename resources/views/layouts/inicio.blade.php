<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    @foreach ($datos as $item)
        <title>{{ $item->nombre }}</title>
        <link rel="shortcut icon" href="{{ asset("foto/empresa/$item->foto") }}" type="image/x-icon">
    @endforeach

    <script src="https://kit.fontawesome.com/646ac4fad6.js" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
    <script src="{{ asset('app/publico/js/lib/jquery/jquery.min.js') }}"></script>
    <style>
        :root {
            --navbarBackground: #2b549c;
            --navbarActivo: #ffffff15;
            --navbarHover: #ffffff15;
        }

        .navbar {
            background: var(--navbarBackground);
            position: fixed;
            top: 0;
            width: 100%;
            z-index: 999;
            height: 60px;
        }

        .nav-link:hover {
            background: var(--navbarHover);
        }

        .nav-link.active {
            background: var(--navbarActivo) !important;
        }

        .fondo {
            width: 100%;
            min-height: 170px;
            background-size: cover;
            background-image: url({{ asset('img/fondo.png') }});
            margin-top: 60px;
        }

        .logo {
            width: 100%;
            max-width: 175px;
            height: auto;
        }

        .smaller-logo {
            width: 100%;
            max-width: 100px;
            height: auto;
        }

        h1 {
            font-size: 2.5rem;
            text-shadow: 0px 0px 5px black, 1px 1px 5px black, 2px 2px 5px black;
        }

        .desc {
            font-size: 1.25rem;
            text-shadow: 0px 0px 5px black, 1px 1px 5px black, 2px 2px 5px black;
        }

        .container {
            width: 100%;
            max-width: 680px;
            background: white;
            margin: auto;
            padding: 20px;
        }

        .form {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        input {
            padding: 10px;
            outline: none;
            font-size: 15px;
        }

        input:focus {
            font-style: normal;
            font-weight: bold;
        }

        input::placeholder {
            font-weight: normal;
        }

        .entrada {
            padding: 12px 8px;
            outline: none;
            color: white;
            font-size: 15px;
            cursor: pointer;
            width: 100%;
            text-decoration: none;
            text-align: center;
            background: rgb(0, 119, 199);
        }

        .entrada:hover {
            background: rgb(1, 137, 227);
        }

        p.title {
            text-align: center;
            font-weight: bold;
            color: rgb(9, 17, 41);
            padding: 0;
            margin: 0;
        }

        .login {
            font-style: italic;
            font-size: 20px;
            font-weight: bold;
            color: rgb(0, 121, 235);
        }

        .group__button {
            width: 100%;
            padding: 0;
            display: flex;
        }

        .marca {
            width: 100%;
            margin: 0;
            background: #084f97;
            position: fixed;
            bottom: 0;
            z-index: 999;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 5px;
        }

        .marca__parrafo {
            margin: 0 !important;
            color: white;
            font-size: 11px;
            font-weight: normal;
        }

        .marca__texto {
            color: rgb(0, 162, 255);
            text-decoration: underline;
        }

        .marca__parrafo span {
            color: red;
        }

        .activo {
            background: var(--navbarActivo);
        }

        .activo:hover {
            background: var(--navbarHover);
        }

        .nav-pills {
            background: var(--navbarBackground);
        }

        .nav-pills a {
            color: #e0dddd;
        }

        .nav-pills a:hover {
            background: var(--navbarHover);
            color: white !important;
        }

        a.active {
            background: rgba(255, 255, 255, 0.308);
        }

        .card-title {
            font-size: 16px;
        }

        .card-text {
            font-size: 15px;
        }

        @media screen and (max-width: 675px) {
            h1 {
                font-size: 1.75rem;
            }
        }

        @media screen and (max-width: 510px) {
            .container {
                width: 90%;
            }
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <button class="navbar-toggler bg-light" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse px-5" id="navbarNavDropdown">
                <ul class="navbar-nav gap-1">
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 {{ Request::is('/') ? 'activo' : '' }}" aria-current="page"
                            href="{{ route('welcome') }}"><i class="fa-solid fa-house"></i> Inicio</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white px-3 {{ Request::is('busqueda*', 'vistaPrevia*') ? 'activo' : '' }}"
                            aria-current="page" href="{{ route('busqueda.index') }}"><i class="fa-solid fa-search"></i>
                            Certificados</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="fondo d-flex flex-column flex-md-row justify-content-center align-items-center text-center">
        @foreach ($datos as $item)
            <div class="p-3">
                <img class="logo img-fluid" src="{{ asset('foto/empresa/' . $item->foto) }}" alt="Logo Empresa">
            </div>
            <div class="p-3 text-white">
                <h1 class="text-center">"{{ $item->nombre }}"</h1>
                <p class="desc">Plataforma de Certificación Digital</p>
            </div>
            <div class="p-3">
                <img class="smaller-logo img-fluid" src="https://vra-certificados.unap.edu.pe/webinar2/logo_unap.webp" alt="Logo UNAP">
            </div>
        @endforeach
    </div>

    <main>
        @yield('contenido')
    </main>

    <footer class="marca">
        @foreach ($datos as $item)
            <p class="marca__parrafo">Copyright (c) {{ Date('Y') }} {{ $item->nombre }}</p>
        @endforeach
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous">
    </script>
</body>

</html>
