@extends('base')

@section('title', 'Ventes')
@section('content')
<section class="mb-5">
    {{-- <form action="" method="get" class=" d-flex gap-3">
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
    </form> --}}
</section>
@php
use Carbon\Carbon;
// $totalDepense = 0;
// // dd($depenses);
// foreach ($depenses as $date => $depense) {
//     // echo $date. "\n";
//     foreach ($depense as $dep) {
//         $totalDepense += $dep->montant;
//     }
// }
// $dy = null;
// foreach ($depenses as $dateYear => $depense) {
//     $dy = Carbon::createFromFormat('m-Y', $dateYear)->format('Y');
// }
@endphp

<div class="row d-flex justify-content-center">
    @php
        $date = null;

// foreach ($journal as $dat => $data) {
//     $date = $dat;
//     echo $date. '<br/>';
//     foreach ($data as $key => $value) {
//         echo $key[0] . ' : ' .'<br/>';
        
//         foreach ($key as $depenses => $depense) {
//             echo $depenses . ' : ' .'<br/>';
//         } 
//     }
// }
    //     $total_amount_year = [];
    // //    $totalKey = '';
    //     foreach ($ventes_year as $key => $venteGroup) {
    //         $total = 0;
    //         foreach ($venteGroup as $vente) {
    //             $total += $vente->nombre * $vente->prix;
    //         }
            
    //         $total_amount_year[$key] = $total;
    //     }
    //     foreach ($total_amount_year as $year => $amount) {
    //         // echo $year . ' : ' . $amount . '<br/>';
    //         if ($dy == $year) {
    //             echo '<h1 class="mx-3 btn btn-outline-primary col-5 d-flex  justify-content-around"><u class="px-3">Total annuel '. $year .': </u> ' . number_format($amount + $totalDepense, 0, '.', ' ' ) . ' FCFA</h1>';
    //         }else{
    //             echo '<h1 class="mx-3 btn btn-outline-primary col-5 d-flex  justify-content-around"><u class="px-3">Total annuel '. $year .': </u> ' . number_format($amount, 0, '.', ' ' ) . ' FCFA</h1>';
    //         }
            
    //     }
@endphp
</div>        
@php
    
@endphp

    <section>
        <div class="row">
            <div class="col-12 col-lg-6">
                @foreach ($depenses as $key => $value) 
                    @php
                    $totalDepense = 0;
                        foreach ($journal[$key]['depenses'] as $depense) {
                            $totalDepense += $depense->montant;
                        }
                    @endphp
                <h1 class="text-center mt-3">Total dépense du {{ $key }} : </h1>
                <div class="d-flex justify-content-center">
                    <button class="mx-auto mb-3 btn btn-outline-primary fs-4 text-center">{{ number_format($totalDepense, thousands_separator: ' ' )}} FCFA</button>
                </div>
                    @foreach ($journal[$key]['depenses'] as $depense)
                        <div class="card mx-auto row col-lg-12 mb-5">
                                <div class="card-header text-center">
                                    <p class="card-text"></p> <span class="fs-3">{{ $depense->libelle }}</span><br>
                                </div>
                                <div class="card-body col-12 d-flex justify-content-center text-dark bg-light bg-gradient mt-2 mb-3">
                                    <p class="card-text fs-3 text-center">
                                        Montant: {{ $depense->montant }} FCFA
                                    </p>
                                </div>
                            </div>
                    @endforeach
                @endforeach
            </div>
            <div class="col-12 col-lg-6">
                @foreach ($ventes as $key => $value) 
                    @php
                        $totalVente = 0;
                        foreach ($journal[$key]['ventes'] as $vente) {
                            $totalVente += $vente->nombre * $vente->prix;
                        }
                        
                    @endphp
                    <h1 class="text-center mt-3">Total vente du {{ $key }}: </h1>
                    <div class="d-flex justify-content-center">
                        <button class="mx-auto mb-3 btn btn-outline-primary fs-4 text-center">{{ number_format($totalVente, thousands_separator: ' ' )}} FCFA</button>
                    </div>
                    
                    @foreach ($journal[$key]['ventes'] as $vente)
                    <div class="card mx-auto row col-lg-12 mb-5">
                        <div class="card-header col-12 d-flex justify-content-center">
                        {{-- @if (isset($date) && $date ==  $key)
                            <h1>{{ "Mois-Année: " . $key . " / Total : " . $totalMonth +  $totalDepense . "FCFA"}}</h1>
                        @else
                            <h1>{{ "Mois-Année: " . $key . " / Total : " . $totalMonth . "FCFA"}}</h1>
                        @endif --}}
                        @if ($vente->designation)
                            <p class="card-text fs-3 text-center">
                                <span class="fw-bold">Désignation: </span>{{ $vente->designation }}
                            </p>
                        @elseif ($vente->produit)
                            <p class="card-text fs-3 text-center">
                                <span class="fw-bold">Désignation: </span> {{ $vente->produit->designation }}
                            </p>
                        @endif 
                    </div>
                        
                            <div class="card-body text-dark bg-light bg-gradient mt-2 mb-3">
                                <div class="border border-5">
                                    {{-- @if ($vente->designation)
                                        <p class="card-text fs-3 text-center">
                                            <span class="fw-bold">Désignation: </span>{{ $vente->designation }}
                                        </p>
                                    @elseif ($vente->produit)
                                    <p class="card-text fs-3 text-center">
                                        <span class="fw-bold">Désignation: </span> {{ $vente->produit->designation }}
                                    </p>
                                    @endif  --}}
                                    @foreach ( $vente->types as $type )
                                        <span class="d-flex w-100 align-items-center justify-content-center"></span>
                                        <p class="card-text fs-3 text-center"><span class="fw-bold">Type: </span> {{ $type->name }}</p>
                                    @endforeach 
                                    <p class="card-text fs-3 text-center"><span class="fw-bold">Nombre: </span>{{ $vente->nombre }}</p>
                                    <p class="card-text fs-3 text-center"><span class="fw-bold">Prix: </span>{{ $vente->prix }}</p>
                                    <p class="card-text fs-3 text-center"><span class="fw-bold">Total: </span>{{ $total = $vente->prix * $vente->nombre }}</p>
                                    <p class="card-text fs-3 text-center"><span class="fw-bold">User: </span>{{ $vente->user->name }}</p>
                                    
                                    {{-- @if (isset($date) && $date ==  $key)
                                        <p class="card-text fs-3 text-center"><span class="fw-bold">Total des dépenses : </span> {{ $totalDepense }}</p> 
                                    @else
                                        <p class="card-text fs-3 text-center"><span class="fw-bold">Pas de dépense </span></p> 
                                    @endif --}}
                                    {{-- <p class="card-text fs-3 text-center"><span class="fw-bold">Date: </span>{{ $key }}</p> --}}
                                </div>
                            </div>
                        </div>    
                        @endforeach
                @endforeach
            </div>
        </div>
    </section>
@endsection

