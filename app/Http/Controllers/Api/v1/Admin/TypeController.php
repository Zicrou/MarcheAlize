<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\TypeFormRequest;
use App\Models\Type;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
class TypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return[
            "types" => Type::where('user_id', $request->user()->id)->get()
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(TypeFormRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;
        $type = Type::create($data);

        return [
            "type" => $type,
            'message' => 'Le type a été créé',
            'success' => true
            ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(TypeFormRequest $request, Type $type)
    {
        $validated = $request->validated();
        $validated['user_id'] = $request->user()->id;
        $type->update($validated);
        return [
            "type" => $type,
            'message' => 'Le type a été modifié',
            'success' => true
            ];
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Type $type)
    {
        $operation_type = DB::table('operation_type')->where('type_id', $type->id)->count();
        
        if($operation_type != 0){
            return [
                "type" => $type,
                'error', 'Le type est utilisé dans une vente'
            ];
        }
        $type->delete();
        return [
            "success" => true,
            'message' => 'Le type a été supprimé'
            ];
    }
}
