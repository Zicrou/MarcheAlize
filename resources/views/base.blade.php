<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="author" content="Mark Otto, Jacob Thornton, and Bootstrap contributors">
    {{-- <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script> --}}
    {{-- @vite(['resources/css/app.css', 'resources/js/app.js']) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>@yield('title') | Mon agence Administration</title>
</head>
<body>
  @php
    $route = request()->route()?->getName() ?? '';
  @endphp
  <header>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
      <div class="container">
        <a class="navbar-brand fw-bold fs-4 text-uppercase" href="{{ url('/') }}">Commerçame</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavbar" aria-controls="mainNavbar" aria-expanded="false" aria-label="Ouvrir le menu">
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="mainNavbar">
          <ul class="navbar-nav me-auto my-2 my-lg-0">
            <li class="nav-item">
              <a href="{{ url('/') }}" @class(["nav-link", "px-lg-3", "active" => $route === ''])>Accueil</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle px-lg-3 {{ str_contains($route, 'produit.') || str_contains($route, 'type.') || str_contains($route, 'operation.') || str_contains($route, 'depense.') || str_contains($route, 'journal.') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                Gestion
              </a>
              <ul class="dropdown-menu dropdown-menu-dark shadow">
                <li><a href="{{ route('admin.produit.index') }}" @class(["dropdown-item", "active" => str_contains($route, 'produit.')])>Stock</a></li>
                <li><a href="{{ route('admin.type.index') }}" @class(["dropdown-item", "active" => str_contains($route, 'type.')])>Types</a></li>
                <li><a href="{{ route('operation.index') }}" @class(["dropdown-item", "active" => str_contains($route, 'operation.')])>Opérations</a></li>
                <li><a href="{{ route('depense.depense.index') }}" @class(["dropdown-item", "active" => str_contains($route, 'depense.')])>Dépenses</a></li>
                <li class="dropend">
                  <a class="dropdown-item dropdown-toggle {{ str_contains($route, 'journal.') ? 'active' : '' }}" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">Journal</a>
                  <ul class="dropdown-menu dropdown-menu-dark shadow">
                    <li><a href="{{ route('admin.journal.operations') }}" @class(["dropdown-item", "active" => str_contains($route, 'operation.')])>Opérations</a></li>
                    <li><a href="{{ route('admin.journal.depenses') }}" @class(["dropdown-item", "active" => str_contains($route, 'depense.')])>Dépenses</a></li>
                  </ul>
                </li>
              </ul>
            </li>
            <li class="nav-item">
              <a href="#" class="nav-link px-lg-3">Contact</a>
            </li>
          </ul>
          <div class="d-flex flex-column flex-lg-row gap-2">
            @guest
              <a href="{{ route('login') }}" class="btn btn-outline-light">Se connecter</a>
              <a href="{{ route('register') }}" class="btn btn-info text-dark">S'inscrire</a>
            @else
              <span class="navbar-text text-light">Bonjour, {{ Auth::user()->name }}</span>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-outline-light">Se déconnecter</button>
              </form>
            @endguest
          </div>
        </div>
      </div>
    </nav>
  </header>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session(key: 'success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="my-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error}}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        @yield('content')
    </div>

    <script>
        // new TomSelect('select');
        // new TomSelect('selectProduit');
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>