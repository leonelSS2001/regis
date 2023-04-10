<?php

namespace App\Http\Controllers;

use App\Models\DetallePedido;
use App\Models\Pedido;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try{
            $pedido = Pedido::all();
            $response = $pedido->toArray();
            $i=0;
            foreach($pedido as $ea){
                $response[$i]["usuario"] = $ea->user->toArray();
                $response[$i]["producto"] = $ea->producto->toArray();
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
        return view('admin.pedido');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try{
            $errores = 0;
            DB::beginTransaction();
            $ae = new Pedido();
            $ae->fecha =$request->fecha;
            $ae->monto = $request->monto;
            $ae->user_id =  $request->user['id'];
            $ae->producto_id =  $request->producto['id'];
            if($ae->save()<=0){
            $errores++;    
            }
            $detalle = $request->Pedido;
            foreach($detalle as $key => $det){
                $detallePedido = new DetallePedido();
                $detallePedido->cantidad = "1";
                $detallePedido->fecha_pedido = $det["fecha_pedido"];
                $detallePedido->pedido_id = $ae->id;
                if($detallePedido->save()<=0){
                    $errores++;
                   }
                   if ($errores == 0){
                    DB::commit();
                    return response()->json(['status'=>'ok', 'data'=>$ae], 200);
                }else{
                    DB::rollback();
                    return response()->json(['status'=>'fail', 'data'=>$detallePedido], 409);
                }
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
            $pedido = Pedido::findOrfail($id);
            return $pedido;
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
            $pedido = Pedido::findOrfail($id);
            $pedido->fecha = $request->fecha;
            if($pedido->update() >=1){
                return response()->json(['status' =>'ok','data'=>$pedido]);
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
        //
    }
}
