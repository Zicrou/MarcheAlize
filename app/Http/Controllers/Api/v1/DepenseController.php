<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\DepenseFormRequest;
use Illuminate\Http\Request;
use App\Models\Depense;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DepenseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $startDate = now()->startOfDay();
        $endDate = now()->endOfDay();
        $query = Depense::query()->whereBetween('created_at', [$startDate, $endDate])->where('user_id', Auth::user()->id)->orderBy('created_at', 'desc');
        return[
            "depenses" => $query->get(),
        ];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $depense = new Depense();
        $depense->fill([
            'user_id' => User::first()->id,
        ]);
        return view("depenses.form",[
            "depense" => $depense,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(DepenseFormRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        
        $depense = Depense::create($data);
        return [
            "depense" => $depense,
            'message' => 'La dépense a été créée',
            "success" => true
        ];
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
    public function edit(Depense $depense)
    {
        return ['depense' => $depense];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(DepenseFormRequest $request, Depense $depense)
    {
        $depense->update($request->validated());
        return [
            'depense' => $depense,
            'message' => 'La dépense a été modifiée',
            'success' => true
        ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $depense)
    {
        $depens = Depense::find($depense);
        if($depens){
            $depens->delete();
            return [
                'message' => 'La dépense a été supprimé',
                'success' => true
            ];
        }else{
            return ["message" => "Impossible d'effectuer cette opération"];
        }
    }
}
