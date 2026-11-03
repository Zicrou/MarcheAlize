<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vente;
use App\Models\Depense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $depenseTotal = 0;
        // Add Depenses de chaque jour dans Journal
        
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $depenses = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $queryDepenses = $depenses->groupBy(function($m) {
            return $m->created_at->format('d-m-Y');
        });
        $journal = [];
        
        $vents = Vente::all();
        $ventes = $vents->groupBy(function($m) {
            return $m->created_at->format('d-m-Y');
        });

        $deps = Depense::where('user_id', Auth::user()->id)->orderBy('created_at', 'desc')->get();
        $allDepenses = $deps->groupBy(function($m) {
            return $m->created_at->format('d-m-Y');
        });
        
        foreach ($allDepenses as $date => $depenses) {
            $journal[$date]['depenses'] = $depenses;
            $journal[$date]['ventes'] = collect();
        }

        foreach ($ventes as $date => $ventesDuJour) {
            if (isset($journal[$date])) {
                $journal[$date]['ventes'] = $ventesDuJour;
            } else {
                $journal[$date]['depenses'] = collect();
                $journal[$date]['ventes'] = $ventesDuJour;
            }
            
        }
        // $vents_year = Vente::where('statut', 1)->get();
        $ventes_year = $vents->groupBy(function($m) {
            return $m->created_at->format('Y');
        });
        
        return view("admin.journaux.index", [
            'ventes' =>   $ventes, 
            'ventes_year' => $ventes_year,
            'journal' => $journal, 
            'depenses' => $allDepenses, 
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
