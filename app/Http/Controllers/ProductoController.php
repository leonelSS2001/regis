<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $producto = Producto::all();
            $response = $producto->toArray();
            $i=0;
            foreach($producto as $ea){
                $response[$i]["proveedor"] = $ea->proveedor->toArray();
                $response[$i]["categoria"] = $ea->categoria->toArray();
                $i++;  
            }
            return $response;
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.productos');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $producto = new Producto();
            $producto->nombre = $request->nombre;
            $producto->precio = $request->precio;
            $producto->existencia = $request->existencia;
            $producto->imagen =$request->imagen;
            $producto->proveedor_id = $request->proveedor['id'];
            $producto->categoria_id = $request->categoria['id'];
            if($producto->save() >= 1){
                return response()->json(['status'=>'ok','data'=>$producto],201);
            }else{
                return response()->json(['status'=>'fail','data'=>null],409);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $producto = Producto::findOrfail($id);
            return $producto;
        }catch(\Exception $e){
            return $e->getMessage();
        }
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
        try{
            $producto = Producto::findOrfail($id);
            $producto->nombre = $request->nombre;
            $producto->precio = $request->precio;
            $producto->existencia = $request->existencia;
            $producto->proveedor_id = $request->proveedor['id'];
            $producto->categoria_id = $request->categoria['id'];
            if($producto->update() >=1){
                return response()->json(['status' =>'ok','data'=>$producto]);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try{
            $ca = Producto::findOrFail($id);
            if($ca->delete()>=1){
                return response()->json(['status'=>'ok', 'data'=>null], 200);
            }
        }catch(\Exception $e){
            return $e->getMessage();
        }
    }
}
