@extends('base')

@section('title', 'Ventes')
@section('content')
<section class="mb-5">
    <form action="" method="get" class=" d-flex gap-3">
    <div class="container">
        <div class="row align-items-center justify-content-center">
                <div class="col-12 col-lg-6 mb-3">
                    <input type="text" placeholder="Mot clef" class="form-control" name="title" value="{{ $input['title'] ?? ''}}">
                </div>
                <div class="col-12 col-lg-6 mb-3">
                    <input type="number" placeholder="Budget max" class="form-control" name="price" value="{{ $input['price'] ?? ''}}">
                </div>
            </div>
            <div class="d-flex justify-content-center align-items-center">
                <button class="btn btn-primary px-5 py-3 d-inline-block">
                    Rechercher
                </button>
            </div>
        </div>
    </form>
</section>


<section class="container py-3 mb-5">
    <div class="row">
        <div class="row align-items-center justify-content-center">
            <div class="row">
                <div class="card">
                    <div class="row">
                        <div class="col-12 col-lg-7 my-3 d-flex justify-content-start">
                            <h1 class="">
                                Total: 
                                {{ number_format($totalOfTheDay, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA
                            </h1>
                        </div>
                        <div class="col-12 col-lg-5">
                            <h5 class="d-flex col-xm-6 justify-content-end">Vente:{{ number_format($totalVenteOfTheDay, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</h5>
                            <h5 class="d-flex col-xm-6 justify-content-end">Réparation: {{ number_format($totalReparationOfTheDay, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</h5>
                            <h5 class="d-flex col-xm-6 justify-content-end">Dépenses journaliére: {{ number_format($depenseTotal, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row col-10 mx-auto d-flex justify-content-center">
    <a href="{{ route('operation.create')}}" class="btn btn-primary col-md-2 mb-3 fs-5">Ajouter</a>
</div>

    <div class="card mb-4">
        <div class="card-body p-2 p-md-4">
        <div class="text-center">
            <p class="fs-5 mb-3">Ventes et réparations du jour :</p>
            <div class="table-responsive">
            <table class="table table-bordered w-100 fs-5 align-middle mb-0" style="min-width: 760px;">
                <thead>
                    <tr>
                        <th>Intitulé</th>
                        <th>Type</th>
                        <th>Nombre</th>
                        <th>Prix</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($operations as $operation)
                        <tr>
                            @if ($operation->designation)
                                <td>{{ $operation->designation }}</td>
                            @else
                                <td>{{ $operation->produit->designation }}</td>
                            @endif
                            <td>{{ $operation->type }}</td>
                            <td>{{ $operation->nombre }}</td>
                            
                            <td>{{ number_format($operation->prix, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</td>
                            <td>{{ number_format($operation->prix * $operation->nombre, thousands_separator: ' ', decimal_separator: '.', decimals: 2) }} FCFA</td>
                            <td>
                                <form action="{{ route('operation.destroy', $operation->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm text-nowrap">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            </div>
        </div>
        </div>
    </div>

    <div class="my-4">
        {{ $operations->links() }}
    </div>



@endsection
