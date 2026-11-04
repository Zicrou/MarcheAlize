<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    {{-- <link href="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.1/dist/js/tom-select.complete.min.js"></script> --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <title>@yield('title') | Administration</title>
</head>
<body>
    <nav class="navbar navbar-expand-lg bg-primary navbar-dark">
        <div class="container-fluid">
          <a class="navbar-brand" href="/">Welcome</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          @php
              $route = request()->route()->getName();
          @endphp
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              {{-- <li class="nav-item">
                <a href="{{ route("admin.produit.index")}}" @class(["nav-link", "active" => str_contains($route, 'produit.')]) aria-current="page">Produits</a>
              </li> --}}
              <li class="nav-item">
                <a href="{{ route("admin.type.index")}}" @class(["nav-link", "active" => str_contains($route, 'type.')]) aria-current="page">Types</a>
              </li>
              <li class="nav-item">
                <a href="{{ route("boutique.vente.index")}}" @class(["nav-link", "active" => str_contains($route, 'boutique.')]) aria-current="page">Ventes</a>
              </li>
              <li class="nav-item">
                <a href="{{ route("admin.journal.index")}}" @class(["nav-link", "active" => str_contains($route, 'journal.')]) aria-current="page">Journal</a>
              </li>
            </ul>
            <div class="ms-auto">
              @auth
              <ul class="navbar-nav">
                <li class="nav-item ">
                  {{-- <a href="{{ route('dashboard.index') }}" @class(["nav-link", "fw-bold"]) aria-current="page">Dashboard</a> --}}
                </li>
              </ul> 
              @elseguest
              <ul class="navbar-nav">
                <li class="nav-item ">
                  {{-- <a href="{{ route('login') }}" @class(["nav-link", "fw-bold"]) aria-current="page">Se connecter</a> --}}
                </li>
                <li class="nav-item ">
                  {{-- <a href="{{ route('register') }}" @class(["nav-link", "fw-bold"]) aria-current="page">S'inscrire</a> --}}
                </li>
              </ul> 
              @endauth
            </div>
          </div>
        </div>
    </nav>
    <div class="container mt-5">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
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
      // new TomSelect("#types", {plugins: {remove_button: {title: 'Supprimer'}}}); 
      new TomSelect("#produit_id", {plugins: {remove_button: {title: 'Supprimer'}}});
    </script>
</body>
</html>