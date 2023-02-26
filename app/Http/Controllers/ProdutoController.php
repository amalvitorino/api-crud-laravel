<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProdutoStoreRequest;
use App\Models\Produto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\CommonMark\Extension\CommonMark\Node\Inline\Strong;

class ProdutoController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //Todos productos
        $produtos = Produto::all();

        //return Json Response
        return response()->json([
            'produtos' => $produtos
        ], 200);
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
    public function store(ProdutoStoreRequest $request)
    {
        try {
            $imagem = Str::random(32) . "." . $request->imagem->getClientOriginalExtension();
            //create product
            Produto::create([
                'nome' => $request->nome,
                'descricao' => $request->descricao,
                'preco' => $request->preco,
                'imagem' => $imagem
            ]);

            //save Image in Store folder
            Storage::disk('public')->put($imagem, file_get_contents($request->imagem));

            //return Json Response
            return response()->json([
                'message' =>  "produto salvo com sucesso."
            ], 200);
        } catch (\Exception $e) {
            //return Json Response
            return response()->json([
                'message' => "Algo deu errado!"
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'message' => "Produto nao encotrado"
            ], 404);
        }

        //rturn Json Response
        return response()->json([
            'produto' => $produto
        ], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(produto $produto)
    {
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProdutoStoreRequest $request, $id)
    {
        try {

            $produto = Produto::find($id);

            if (!$produto) {
                return response()->json([
                    'message' => "Produto nao encotrado"
                ], 404);
            }

            $produto->nome  = $request->nome;
            $produto->descricao  = $request->descricao;
            $produto->preco  = $request->preco;

            if($request->imagem){
                //public storege
                $storage = Storage::disk('public');

                //old image delete
                if($storage->exists($produto->imagem)){
                    $storage->delete($produto->imagem);
                }

                //image name
                $imageName = Str::random(32) . "." . $request->imagem->getClientOriginalExtension();
                $produto->imagem = $imageName;

                //image save in public folder
                $storage->put($imageName, file_get_contents($request->imagem));
            }

            //update product
            $produto->save();

            //return Json Response
            return response()->json([
                'message' => "Producto actualizado com sucessso"
            ],200);

        } catch (\Exception $e) {
            //return Json Response
            return response()->json([
                'message' => "Algo deu errado!"
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $produto = Produto::find($id);
        if (!$produto) {
            return response()->json([
                'message' => "Produto nao encotrado"
            ], 404);
        }

        //public store
        $storage = Storage::disk('public');

        //Image delete
        if($storage->exists($produto->imagem)){
            $storage->delete($produto->imagem);
        }
        //delete product
        $produto->delete();
        //rturn Json Response
        return response()->json([
            'message' => "Produto eliminado com sucesso"
        ], 200);
    }
}
