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
        // $client=$request['cliente'];
        // $clientName=$client['nombres'];
        // $clientEmail=$client['email'];
        // $clientBusiness=$client['empresa'];
        // $tipoCliente=1;
        // $cotizationStatus="Pendiente";
        // $dataReturn=[];
        // $cotizationID=DB::table('carga_consolidada_cotizaciones_cabecera')->insertGetId([
        //     'N_Cliente' => $clientName,
        //     'Empresa' => $clientBusiness,
        //     'Fe_Creacion' => $currentDate,
        //     'ID_Tipo_Cliente' => $tipoCliente,
        //     "Cotizacion_Status" =>$cotizationStatus,
            
        // ]);
        // //get id of type tributes table 
        // $tributesIdObject=DB::table('tipo_carga_consolidada_cotizaciones_tributo')->select('ID_Tipo_Tributo')->get();
        // $tributesIdArray= array_column(json_decode($tributesIdObject, true), 'ID_Tipo_Tributo');
        // $dataReturn[$cotizationID]=[];
        // $proveedores=$request['proveedores'];
        // foreach($proveedores as $pv){
        //     doubleval($CBM=$pv['indicators']['cbm']);
        //     doubleval($peso=$pv['indicators']['peso']);
        //     $proveedorID=DB::table('carga_consolidada_cotizaciones_detalles_proovedor')->insertGetId([
        //         'ID_Cotizacion' => $cotizationID,
        //         'CBM_Total' => $CBM,
        //         'Peso_Total' => $peso,
        //     ]);
        //     $dataReturn[$cotizationID][$proveedorID]=[];
        //     foreach($pv['products'] as $prod){
        //         $nombreComercial=$prod['name'];
        //         $uso=$prod['uso'];
        //         $cantidad=$prod['cantidad'];
        //         $link=$prod['link'];
        //         $productoID=DB::table('carga_consolidada_cotizaciones_detalles_producto')->insertGetId([
        //             'ID_Proveedor' => $proveedorID,
        //             "ID_Cotizacion" => $cotizationID,
        //             'Nombre_Comercial' => $nombreComercial,
        //             'Uso' => $uso,
        //             'Cantidad' => $cantidad,
        //             'URL_Link' => $link,
        //         ]);
        //         array_push($dataReturn[$cotizationID][$proveedorID],$productoID);
        //         foreach($tributesIdArray as $tribute){
        //             DB::table('carga_consolidada_cotizaciones_detalles_tributo')->insertGetId([
        //                 'ID_Proveedor' => $proveedorID,
        //                 "ID_Cotizacion" => $cotizationID,
        //                 'ID_Tipo_Tributo' => $tribute,
        //                 "ID_Producto" => $productoID,
        //             ]);
        //         }   
        //     }
            /* new data structure 
            nombres: 2
            whatsapp: 2
            dni: 1
            empresa: 21
            email: 12
            ruc: 121
            proveedor-0-cbm: 121
            proveedor-0-peso: 212
            proveedor-0-proforma: null
            proveedor-0-producto-0-nombre: 1211
            proveedor-0-producto-0-uso: 1212
            proveedor-0-producto-0-cantidad: 1212
            proveedor-0-producto-0-foto: (binary)
            proveedor-0-producto-0-link: 12
            proveedor-0-producto-1-nombre: 1211
            proveedor-0-producto-1-uso: 1212
            proveedor-0-producto-1-cantidad: 1212
            proveedor-0-producto-1-foto: (binary)
            proveedor-0-producto-1-link: 12*/

            $clientName=$request['nombres'];
            // $clientEmail=$client['email'];
            $clientBusiness=$request['empresa'];
            $tipoCliente=1;
            $cotizationStatus="Pendiente";
       
            $cotizationID=DB::table('carga_consolidada_cotizaciones_cabecera')->insertGetId([
                    'N_Cliente' => $clientName,
                    'Empresa' => $clientBusiness,
                    'Fe_Creacion' => $currentDate,
                    'ID_Tipo_Cliente' => $tipoCliente,
                    "Cotizacion_Status" =>$cotizationStatus,   
                ]);
                foreach ($request->all() as $key => $value) {
                    if (strpos($key, 'proveedor-') === 0) {
                        // Si la clave comienza con 'proveedor-', significa que es un proveedor
                        // Extrae el ID del proveedor del nombre de la clave
                        $matches = [];
                        preg_match('/proveedor-(\d+)-/', $key, $matches);
                        $proveedorIndex = intval($matches[1]);
                        
                        // Inserta los datos del proveedor en la tabla correspondiente
                        $CBM = $request->input("proveedor-{$proveedorIndex}-cbm");
                        $peso = $request->input("proveedor-{$proveedorIndex}-peso");
                        
                        $proveedorID = DB::table('carga_consolidada_cotizaciones_detalles_proovedor')->insertGetId([
                            'ID_Cotizacion' => $cotizationID,
                            'CBM_Total' => $CBM,
                            'Peso_Total' => $peso,
                        ]);
                
                        // Itera sobre los datos de los productos del proveedor
                        $productoIndex = 0;
                            // Extrae los datos del producto del proveedor
                            
                            $nombreComercial = $request->input("proveedor-{$proveedorIndex}-producto-{$productoIndex}-nombre");
                            $uso = $request->input("proveedor-{$proveedorIndex}-producto-{$productoIndex}-uso");
                            $cantidad = $request->input("proveedor-{$proveedorIndex}-producto-{$productoIndex}-cantidad");
                            $foto = $request->file("proveedor-{$proveedorIndex}-producto-{$productoIndex}-foto"); // Maneja el archivo de imagen
                            $link = $request->input("proveedor-{$proveedorIndex}-producto-{$productoIndex}-link");
                            //mange foto , upload to server and get url 
                            $request->validate([
                                "proveedor-{$proveedorIndex}-producto-{$productoIndex}-foto" => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                            ]);
                            $url = $request->file('proveedor-{$proveedorIndex}-producto-{$productoIndex}-foto')->store('public');

                            // Cambiar la URL para que sea accesible públicamente
                            $url = str_replace('public', 'storage', $url);
                    
                            // Devolver la URL del archivo cargado
                            
                            // Inserta los datos del producto en la tabla correspondiente
                            $productoID = DB::table('carga_consolidada_cotizaciones_detalles_producto')->insertGetId([
                                'ID_Proveedor' => $proveedorID,
                                "ID_Cotizacion" => $cotizationID,
                                'Nombre_Comercial' => $nombreComercial,
                                'Uso' => $uso,
                                'Cantidad' => $cantidad,
                                'URL_Link' => $link,
                                'URL_Image' => $url,
                            ]);
                            
                            $productoIndex++;
                            //end while

                        }
                    }
                
                DB::commit();
                // return response()->json([
                //     'message' => 'Cotización creada correctamente',
                // ], 201);  
    }catch(\Exception $e){
        DB::rollBack();
        return response()->json([
            'message' => 'Error al crear cotización',
            'error' => $e->getMessage()
        ], 500);
    } 
}
}
