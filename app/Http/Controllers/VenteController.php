<?php

namespace App\Http\Controllers;;

use App\Http\Controllers\Controller;
use App\Http\Requests\VenteFormRequest;
use App\Http\Requests\SearchVentesRequest;
use App\Models\Produit;
use App\Models\Type;
use App\Models\User;
use App\Models\Vente;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VenteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(SearchVentesRequest $request)
    {
        $depenseTotal = 0;
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $queryDepenses = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        foreach ($queryDepenses as $qd ) {
            $depenseTotal += $qd->montant;
        }
        // dd($depenseTotal);
        $query = Vente::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        if ($price = $request->validated('price')) {
			$query->where('prix', '<=', $price);
		}
        if ($title = $request->validated('title')) {
            $query->with('produit')->whereHas('produit', function ($query) use ($title) {
                $query->where('designation', 'like', "%{$title}%");
            });
		}
        //  $totalOfTheDay = $query->sum("prix");
         $totalOfTheDay = 0;
         $totalVenteOfTheDay= 0;
         $totalReparationOfTheDay= 0;
         $totalVenteEtReparationOfTheDay= 0;
        $ventes = $query->get();
        foreach ($ventes as $vente){
            $total = $vente->prix * $vente->nombre;
            $totalOfTheDay += $total;
            $type_vente = $vente->types()->get();
            foreach ($type_vente as $tv) {
                // dd($tv->name);
                if($tv->id == 1){
                    $total = $vente->prix * $vente->nombre;
                    $totalVenteOfTheDay += $total;
                }elseif ($tv->id == 2) {
                    $total = $vente->prix * $vente->nombre;
                    $totalReparationOfTheDay += $total;
                }elseif ($tv->id == 4) {
                    $total = $vente->prix * $vente->nombre;
                    $totalVenteEtReparationOfTheDay += $total;
                }
            }
        }
		
        return view('ventes.index', [
			'ventes' => $query->paginate(2),
			'input'      => $request->validated(),
            'totalOfTheDay' => $totalOfTheDay,
            'totalVenteOfTheDay' => $totalVenteOfTheDay,
            'totalReparationOfTheDay' => $totalReparationOfTheDay,
            'depenseTotal' => $depenseTotal,
		]);

    //     $ventes = Vente::orderBy('created_at', 'desc')->paginate(1);
    //     return view("ventes.index",
    // ["ventes" => $ventes ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $vente = new Vente();
        $vente->fill([
            'user_id' => User::first()->id,
        ]);
        $produits = Produit::pluck('designation', 'id');
        
        return view('ventes.form', [
            'vente' => $vente,
            'produits' => $produits,
            'types' => Type::pluck('name', 'id'),
            'produits' => $produits,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(VenteFormRequest $request)
    {
        
        $produit = Produit::where('id', $request->validated('produit_id'))->first();
        
        if($request->validated(key: 'designation') and $produit){
            return redirect()->route('boutique.vente.create')->with('error', 'Choisir entre Stock et Désignation');
        }elseif ($request->validated('designation') or $produit) {
            if ($produit) {
                if ($produit->nombre < $request->validated('nombre')){
                    return redirect()->route('boutique.vente.create')->with('error', 'Pas assez de produit de le stock');
                }else{
                    $vente = Vente::create($request->validated());
                    $produit->nombre -= $request->validated('nombre');
                    $produit->save();
                    $vente->types()->sync($request->validated('types'));
                    return to_route('boutique.vente.index')->with('success', 'La vente a été créée');
                }
            }else{
                $vente = Vente::create($request->validated());
                $vente->types()->sync($request->validated('types'));
                return to_route('boutique.vente.index')->with('success', 'La vente a été créée');
            }
        }
    }

    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        return view('ventes.form', [
            'vente' => $vente, 
            'types' => Type::pluck('name', 'id'),
            'produits' => Produit::pluck('designation', 'id'),
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(VenteFormRequest $request, Vente $vente)
    {  
        // dd($request->validated()) ;
        if ($request->validated('produit_id')) {
            $produit = Produit::where('id', $request->validated('produit_id'))->first();
            if ($request->validated('nombre') > $vente->nombre) {
                $produit->nombre = $produit->nombre - ($request->validated('nombre') - $vente->nombre);
            }elseif ($request->validated('nombre') < $vente->nombre) {
                $produit->nombre = $produit->nombre + ($vente->nombre - $request->validated('nombre'));
            }
            $produit->save();
        }
        $vente->update($request->validated());
        $vente->types()->sync($request->validated('types'));
        return to_route('boutique.vente.index')->with('success', 'La vente a été modifiée');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        $produit = Produit::find(id: $vente->produit_id);
        if($produit){
            $produit->nombre += $vente->nombre;
            $produit->save();
        }
        $vente->delete();
        return to_route('boutique.vente.index')->with('success', 'La vente a été annulée');
    }
}
