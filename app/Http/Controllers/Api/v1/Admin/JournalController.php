<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Depense;
use App\Models\Operation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class JournalController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->coequipier != null){
            $validated = $request->validate([
                'coequipier' => ['required', 'string', 'exists:users,id']
            ]);
            $queryDepenses = Depense::query()->where('user_id', $validated['coequipier'])->orderBy('created_at', 'desc')->get();
            $queryOperation = Operation::query()->where('user_id', $validated['coequipier'])->orderBy('created_at', 'desc')->get();
            
            }else{
            $queryDepenses = Depense::query()->where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
            $queryOperation = Operation::query()->where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->get();
        }
        $queryDepenses->load('user');
        $queryOperation->load(["user", "types", "produit"]);
        return  [
            'operations' => $queryOperation,
            'depenses' => $queryDepenses,
        ];
        
    }

   public function journalDepense(Request $request){
        if($request->coequipier != null){
            $validated = $request->validate([
                'coequipier' => ['required', 'string', 'exists:users,id']
            ]);
            $depenses = Depense::where('user_id', $validated['coequipier'])->orderBy('created_at', 'desc')->paginate(10);
        }else{
            $depenses = Depense::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        }

        return ['depenses' => $depenses];
    }
    public function journalOperation(Request $request){
        if($request->coequipier != null){
            $validated = $request->validate([
                'coequipier' => ['required', 'string', 'exists:users,id']
            ]);
            $operations = Operation::where('user_id', $validated['coequipier'])->orderBy('created_at', 'desc')->paginate(10);
        }else{
            $operations = Operation::where('user_id', $request->user()->id)->orderBy('created_at', 'desc')->paginate(10);
        }
        return ['operations' => $operations];
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
