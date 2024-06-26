<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
class InitCargaConsolidada extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $tableCabeceraQuery="
        CREATE TABLE carga_consolidada_cotizaciones_cabecera (
            ID_Cotizacion INT NOT NULL,
            Fe_Creacion DATE NOT NULL,
            N_Cliente TEXT(65535) NULL,
            Empresa TEXT(65535) NULL,
            Cotizacion DECIMAL(10,2) NULL,
            ID_Tipo_Cliente INT NOT NULL,
            Cotizacion_Status CHAR(10) NULL
            PRIMARY KEY (ID_Cotizacion)
        )";
        DB::statement($tableCabeceraQuery);
        $tableTipoTributoTable="CREATE TABLE tipo_carga_consolidada_cotizaciones_tributo (
            ID_Tipo_Tributo INT NOT NULL,
            Nombre VARCHAR(255) NULL,
            table_key VARCHAR(50) NULL
            PRIMARY KEY (ID_Tipo_Tributo)
        );" ;
        $insertIntoTipoTributo="INSERT INTO intranetprobusiness.tipo_carga_consolidada_cotizaciones_tributo
        (ID_Tipo_Tributo, Nombre, , table_key)
        VALUES(1, 'Ad Valorem', 'ad-valorem');
        INSERT INTO intranetprobusiness.tipo_carga_consolidada_cotizaciones_tributo
        (ID_Tipo_Tributo, Nombre, tableKey, table_key)
        VALUES(2, 'IGV', 'igv');
        INSERT INTO intranetprobusiness.tipo_carga_consolidada_cotizaciones_tributo
        (ID_Tipo_Tributo, Nombre, tableKey, table_key)
        VALUES(3, 'IPM',  'ipm');
        INSERT INTO intranetprobusiness.tipo_carga_consolidada_cotizaciones_tributo
        (ID_Tipo_Tributo, Nombre, tableKey, table_key)
        VALUES(4, 'PERCEPCION', , 'percepcion');
        INSERT INTO intranetprobusiness.tipo_carga_consolidada_cotizaciones_tributo
        (ID_Tipo_Tributo, Nombre, tableKey, table_key)
        VALUES(5, 'VALORACION', , 'valoracion');
        INSERT INTO intranetprobusiness.tipo_carga_consolidada_cotizaciones_tributo
        (ID_Tipo_Tributo, Nombre, tableKey, table_key)
        VALUES(6, 'ANTIDUMPING',  'antidumping');";
       $tableDetallesProveedor="CREATE TABLE information_schema.carga_consolidada_cotizaciones_detalles_proovedor (
        ID_Proveedor INT NOT NULL,
        ID_Cotizacion INT NOT NULL,
        CBM_Total DECIMAL(10,2) NULL,
        Peso_Total DECIMAL(10,2) NULL,
        URL_Proforma TEXT(65535) NULL,
        URL_Packing TEXT(65535) NULL
        PRIMARY KEY (ID_Proveedor)
        FOREIGN KEY (ID_Cotizacion) REFERENCES carga_consolidada_cotizaciones_cabecera(ID_Cotizacion)
        );";
        $tableDetallesProductos="
        CREATE TABLE information_schema.carga_consolidada_cotizaciones_detalles_producto (
            ID_Producto INT NOT NULL,
            ID_Cotizacion INT NOT NULL,
            ID_Proveedor INT NOT NULL,
            URL_Image TEXT(65535) NULL,
            URL_Link TEXT(65535) NULL,
            Nombre_Comercial VARCHAR(500) NULL,
            Uso TEXT(65535) NULL,
            Cantidad DECIMAL(10,2) NULL,
            Valor_unitario DECIMAL(10,2) NULL
            PRIMARY KEY (ID_Producto)
            FOREIGN KEY (ID_Cotizacion) REFERENCES carga_consolidada_cotizaciones_cabecera(ID_Cotizacion)
            FOREIGN KEY (ID_Proveedor) REFERENCES carga_consolidada_cotizaciones_detalles_proovedor(ID_Proveedor)
        );";  
        $tableDetallesTributo="CREATE TABLE information_schema.carga_consolidada_cotizaciones_detalles_tributo (
            ID_Tributo INT NOT NULL,
            ID_Tipo_Tributo INT NOT NULL,
            ID_Producto INT NOT NULL,
            ID_Proveedor INT NOT NULL,
            ID_Cotizacion INT NOT NULL,
            Status enum('Pending','Completed') NULL default 'Pending',
            value DECIMAL(10,2) NULL,
            PRIMARY KEY (ID_Tributo)
            FOREIGN KEY (ID_Tipo_Tributo) REFERENCES tipo_carga_consolidada_cotizaciones_tributo(ID_Tipo_Tributo)
            FOREIGN KEY (ID_Producto) REFERENCES carga_consolidada_cotizaciones_detalles_producto(ID_Producto)
            FOREIGN KEY (ID_Proveedor) REFERENCES carga_consolidada_cotizaciones_detalles_proovedor(ID_Proveedor)
            FOREIGN KEY (ID_Cotizacion) REFERENCES carga_consolidada_cotizaciones_cabecera(ID_Cotizacion)
        );";
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
