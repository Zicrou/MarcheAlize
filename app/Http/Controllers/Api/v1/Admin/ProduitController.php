<?php

namespace App\Http\Controllers\Api\v1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProduitFormRequest;
use App\Models\Produit;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;


class ProduitController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return [
            "produits" => Produit::orderBy('created_at', 'desc')->get()
        ];
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProduitFormRequest $request)
    {
        $data = $request->validated();
        if (request()->hasFile('image')) {
            if($image = $request->file('image')){
                $filename = $image->getClientOriginalName();
                $imageName = time().$filename;
                $path = 'photos/produits/' . $request->user()->id . '/';
                $data['image'] = $path.$imageName;
                // $image->storeAs($path, $imageName, 'public'); 
                $image->move($path, $imageName);
            }
        }
        $data['user_id'] = auth()->user()->id;
        $produit = Produit::create($data);
        return [
            "produit" => $produit,
            'message' => 'Le produit a été créé',
            "success" => true
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProduitFormRequest $request, Produit $produit)
    {
        $data = $request->validated();
        if (request()->hasFile('image')) {
            if($image = $request->file('image')){
                $filename = $image->getClientOriginalName();
                $imageName = time().$filename;
                $path = 'photos/produits/' . $request->user()->id . '/';
                $data['image'] = $path.$imageName;
                $image->move($path, $imageName);
            }
        }
        if (File::exists($produit->image)) {
            File::delete($produit->image);
        }
        $produit->update($data);
        return [
            "produit" => $produit,
            'message' => 'Le produit a été modifié',
            "success" => true
        ];

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($produit)
    {
        $produit_exist = Produit::find($produit);
        if(!$produit_exist){
            return [
                "message" => "Produit introuvable"
            ];
        }
        $produit->delete();
        return [
            "success" => true,
            'message' => 'Le produit a été supprimé'
        ];
    }

    public function destroyImage($produit)
    {
        $produitImage = Produit::find($produit);
        if($produitImage){
            if (File::exists($produitImage->image)) {
                File::delete($produitImage->image);
            }else{
                return ["message" => "Pas d'image. Veuillez réactualiser"];
            }
            $produitImage->update(['image'=> '']);
            return [
                'message' => 'L\'image du produit a été supprimé',
                "success" => true
            ];
        }
        return ["message" => "Ce produit n'existe pas"];
    }
}
