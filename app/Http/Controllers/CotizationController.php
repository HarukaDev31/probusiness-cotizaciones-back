<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
class CotizationController extends Controller
{
   public function createCotization(Request $request){
    DB::beginTransaction();
    try{
        //date of now 
        $currentDate = Carbon::now();
        $client=$request['cliente'];
        $clientName=$client['nombres'];
        $clientEmail=$client['email'];
        $clientBusiness=$client['empresa'];
        $tipoCliente=1;
        $cotizationStatus="Pendiente";
        $dataReturn=[];
        $cotizationID=DB::table('carga_consolidada_cotizaciones_cabecera')->insertGetId([
            'N_Cliente' => $clientName,
            'Empresa' => $clientBusiness,
            'Fe_Creacion' => $currentDate,
            'ID_Tipo_Cliente' => $tipoCliente,
            "Cotizacion_Status" =>$cotizationStatus,
            
        ]);
        //get id of type tributes table 
        $tributesIdObject=DB::table('tipo_carga_consolidada_cotizaciones_tributo')->select('ID_Tipo_Tributo')->get();
        $tributesIdArray= array_column(json_decode($tributesIdObject, true), 'ID_Tipo_Tributo');
        $dataReturn[$cotizationID]=[];
        $proveedores=$request['proveedores'];
        foreach($proveedores as $pv){
            doubleval($CBM=$pv['indicators']['cbm']);
            doubleval($peso=$pv['indicators']['peso']);
            $proveedorID=DB::table('carga_consolidada_cotizaciones_detalles_proovedor')->insertGetId([
                'ID_Cotizacion' => $cotizationID,
                'CBM_Total' => $CBM,
                'Peso_Total' => $peso,
            ]);
            $dataReturn[$cotizationID][$proveedorID]=[];
            foreach($pv['products'] as $prod){
                $nombreComercial=$prod['name'];
                $uso=$prod['uso'];
                $cantidad=$prod['cantidad'];
                $link=$prod['link'];
                $productoID=DB::table('carga_consolidada_cotizaciones_detalles_producto')->insertGetId([
                    'ID_Proveedor' => $proveedorID,
                    "ID_Cotizacion" => $cotizationID,
                    'Nombre_Comercial' => $nombreComercial,
                    'Uso' => $uso,
                    'Cantidad' => $cantidad,
                    'URL_Link' => $link,
                ]);
                array_push($dataReturn[$cotizationID][$proveedorID],$productoID);
                foreach($tributesIdArray as $tribute){
                    DB::table('carga_consolidada_cotizaciones_detalles_tributo')->insertGetId([
                        'ID_Proveedor' => $proveedorID,
                        "ID_Cotizacion" => $cotizationID,
                        'ID_Tipo_Tributo' => $tribute,
                        "ID_Producto" => $productoID,
                    ]);
                }   
            }
            
        }
        DB::commit();
        return response()->json([
            'message' => 'Cotización creada correctamente',
            "data"=>$dataReturn
        ], 201);

    }catch(\Exception $e){
        DB::rollBack();
        return response()->json([
            'message' => 'Error al crear cotización',
            'error' => $e->getMessage()
        ], 500);
    } 
}
}
