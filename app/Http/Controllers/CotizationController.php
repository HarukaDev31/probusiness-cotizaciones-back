<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CotizationController extends Controller
{
   public function createCotization(Request $request){
    try{
        return response()->json([
            'message' => 'Cotización creada correctamente',
            'data' => $request->all()
        ], 201);
    }catch(\Exception $e){
        return response()->json([
            'message' => 'Error al crear cotización',
            'error' => $e->getMessage()
        ], 500);
    } 
}
}
