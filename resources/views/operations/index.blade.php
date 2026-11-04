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
                                <u class="mx-2 px-0">Total: </u>
                                {{ number_format($totalOfTheDay, thousands_separator: ' ' )}} FCFA
                            </h1>
                        </div>
                        <div class="col-12 col-lg-5">
                            <h5 class="d-flex col-xm-6 justify-content-end"><u class="px-3">Vente:</u>{{ number_format($totalVenteOfTheDay, thousands_separator: ' ' )}} FCFA</h5>
                            <h5 class="d-flex col-xm-6 justify-content-end"><u class="px-3">Réparation:</u> {{ number_format($totalReparationOfTheDay, thousands_separator: ' ' )}} FCFA</h5>
                            <h5 class="d-flex col-xm-6 justify-content-end"><u class="px-3">Dépenses journaliére:</u> {{ number_format($depenseTotal, thousands_separator: ' ' )}} FCFA</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="row col-10 mx-auto d-flex justify-content-center">
    <a href="{{ route('operation.create')}}" class="btn btn-primary col-md-2 mb-3 fs-5">Faire une vente</a>
</div>

    @foreach ($operations as $operation)
        <div class="card mx-auto row col-12 col-lg-12 mb-5"  style="background-color: #F2F2F2;">
            <div class="card-header text-dark">
                @if ($operation->designation)
                    <p class="card-text fs-3 text-center"><span class="fw-bold">Désignation:</span> {{ $operation->designation }}</p>
                @elseif ($operation->produit_id)
                <span class="card-text d-flex mx-auto col-3 col-lg-1 fw-bold fs-3 badge text-bg-primary mb-3">
                    Stock
                </span> 
                    <p class="card-text fs-3 text-center"><span class="fw-bold">Désignation: </span> {{ $operation->produit->designation }}
                    </p>
                @endif
                @foreach ( $operation->types as $type )
                    <span class="d-flex w-100 align-items-center justify-content-center"></span>
                    <p class="card-text fs-3 text-center"><span class="fw-bold">Type: </span> {{ $type->name }}</p>
                @endforeach ()
                <p class="card-text fs-3 text-center"><span class="fw-bold">Nombre: </span> {{ $operation->nombre }}</p>
                <p class="card-text fs-3 text-center"><span class="fw-bold">Prix: </span> {{ $operation->prix }}</p>
                <p class="card-text fs-3 text-center"><span class="fw-bold">Total: </span> {{ $total = $operation->prix * $operation->nombre }} FCFA</p>
                <p class="card-text fs-3 text-center"><span class="fw-bold">User: </span> {{ $operation->user->name }}</p>
            </div>
            <div class="card-footer">
                <div class="row d-flex justify-content-evenly">
                    <div class="col-6 col-lg-5">
                        <a href="{{ route('operation.edit', $operation) }}" class="text-center btn btn-primary">Editer</a>
                    </div>
                    <div class="col-6 col-lg-5 d-flex justify-content-center">
                        <form action="{{ route('operation.destroy', $operation) }}" method="post">
                            @csrf
                            @method('delete')
                            <button class="text-center btn btn-danger">Annuler</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>    
    @endforeach
    <div class="my-4">
        {{ $operations->links() }}
    </div>
@endsection

