<?php
require_once('conexion.php');

class clsVenta{

	function listarVenta($desde, $hasta, $cliente, $idcomprobante, $correlativo){
        $sql = "SELECT v.idventa, v.fecha, v.serie, v.correlativo, v.total, tc.nombre comprobante,
                        c.nombre cliente, v.estado 
                FROM venta v INNER JOIN tipocomprobante tc ON v.idtipocomprobante=tc.idtipocomprobante
                LEFT JOIN cliente c ON v.idcliente=c.idcliente
                WHERE v.estado<2 AND c.nombre LIKE :cliente ";
        $parametros = array(':cliente'=>$cliente);
        if($desde!=""){
            $sql.=" AND v.fecha>=:desde ";
            $parametros[':desde']=$desde;
        }
        if($hasta!=""){
            $sql.=" AND v.fecha<=:hasta ";
            $parametros[':hasta']=$hasta;
        }
        if($idcomprobante!=""){
            $sql.=" AND v.idtipocomprobante=:idtipocomprobante ";
            $parametros[':idtipocomprobante']=$idtipocomprobante;
        }
        if($correlativo!=""){
            $sql.=" AND v.correlativo LIKE :correlativo ";
            $parametros[':correlativo']='%'.$correlativo.'%';
        }

        $sql.=" ORDER BY v.fecha DESC";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function consultarVenta($idventa){
        $sql = "SELECT * FROM venta WHERE idventa=? ";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idventa));
        return $pre;
    }

    function consultarDetalleVenta($idventa){
        $sql = "SELECT d.*, p.nombre 
            FROM detalle d INNER JOIN producto p ON d.idproducto=p.idproducto
            WHERE d.idventa=? AND d.estado<>2 ORDER BY d.iddetalle ASC";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idventa));
        return $pre;
    }

    function consultarVentaExistente($idtipocomprobante, $serie, $correlativo, $idventa=0){
        $sql = "SELECT * FROM venta WHERE idtipocomprobante=? AND serie=? 
                AND correlativo=? AND idventa<>?";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idtipocomprobante,$serie, $correlativo, $idventa));
        return $pre;
    }

    function insertarDetalle($detalle){
        $sql = "INSERT INTO detalle(iddetalle, idventa, idproducto, cantidad, unidad,
                            pventa, igv, icbper, descuento, total, idafectacion, estado)
                VALUES (NULL, :idventa, :idproducto, :cantidad, :unidad,
                            :pventa, :igv, :icbper, :descuento, :total, :idafectacion, :estado)";
        global $cnx;
        $pre = $cnx->prepare($sql);
        
        foreach($detalle as $k=>$v){
            $parametros = array(
                ":idventa"      =>$v["idventa"], 
                ":idproducto"   =>$v["idproducto"], 
                ":cantidad"     =>$v["cantidad"], 
                ":unidad"       =>$v["unidad"],
                ":pventa"       =>$v["pventa"], 
                ":igv"          =>$v["igv"], 
                ":icbper"       =>$v["icbper"], 
                ":descuento"    =>$v["descuento"], 
                ":total"        =>$v["total"], 
                ":idafectacion" =>$v["idafectacion"], 
                ":estado"       =>1
            );
            $pre->execute($parametros);  
        }
    }

    function cambiarEstadoDetalle($idventa, $estado){
        $sql = "UPDATE detalle SET estado=:estado WHERE idventa=:idventa AND estado<>2";
        global $cnx;
        $parametros = array(':idventa'=>$idventa,':estado'=>$estado);
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function actualizarEstado($idventa, $estado){
        $sql = "UPDATE venta SET estado=:estado WHERE idventa=:idventa";
        global $cnx;
        $parametros = array(":idventa"=>$idventa, ":estado"=>$estado);
        $pre= $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function actualizar($venta){
        $sql = "UPDATE venta 
                SET fecha=:fecha, idcliente=:idcliente, idtipocomprobante=:idtipocomprobante,
                    serie=:serie, correlativo=:correlativo, total=:total, 
                    total_gravado=:total_gravado, total_exonerado=:total_exonerado, 
                    total_inafecto=:total_inafecto, total_igv=:total_igv,
                    total_icbper=:total_icbper, total_descuento=:total_descuento, 
                    formapago=:formapago, idmoneda=:idmoneda, vencimiento=:vencimiento, 
                    guiaremision=:guiaremision, ordencompra=:ordencompra, 
                    idusuario=:idusuario, estado=:estado 
                WHERE idventa=:idventa";
        global $cnx;
        $parametros = array(
            ":idventa"          =>$venta["idventa"],
            ":fecha"            =>$venta["fecha"], 
            ":idcliente"        =>$venta["idcliente"], 
            ":idtipocomprobante"=>$venta["idtipocomprobante"], 
            ":serie"            =>$venta["serie"], 
            ":correlativo"      =>$venta["correlativo"], 
            ":total"            =>$venta["total"], 
            ":total_gravado"    =>$venta["total_gravado"],    
            ":total_exonerado"  =>$venta["total_exonerado"], 
            ":total_inafecto"   =>$venta["total_inafecto"], 
            ":total_igv"        =>$venta["total_igv"],
            ":total_icbper"     =>$venta["total_icbper"], 
            ":total_descuento"  =>$venta["total_descuento"], 
            ":formapago"        =>$venta["formapago"], 
            ":idmoneda"         =>$venta["idmoneda"], 
            ":vencimiento"      =>$venta["vencimiento"], 
            ":guiaremision"     =>$venta["guiaremision"], 
            ":ordencompra"      =>$venta["ordencompra"], 
            ":idusuario"        =>$_SESSION["idusuario"], 
            ":estado"           =>$venta["estado"]
        );
        $pre= $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function consultarSeries($idtipocomprobante){
        $sql = "SELECT * FROM serie WHERE idtipocomprobante=? AND estado=1";
        global $cnx;
        $pre= $cnx->prepare($sql);
        $pre->execute(array($idtipocomprobante));
        return $pre;        
    }

    function obtenerCorrelativo($idtipocomprobante, $serie){
        $sql = "SELECT correlativo FROM serie WHERE idtipocomprobante=? AND serie=? AND estado=1";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($idtipocomprobante,$serie));
        if($pre->rowCount()>0){
            $pre = $pre->fetch(PDO::FETCH_NUM);
            $pre = $pre[0]+1;
        }else{
            $pre = 0;
        }
        return $pre;
    }

    function actualizarCorrelativo($idtipocomprobante, $serie, $correlativo){
        $sql = "UPDATE serie SET correlativo=? WHERE idtipocomprobante=? AND serie=? AND estado=1";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($correlativo, $idtipocomprobante,$serie));
        return $pre;
    }

    function listarVentasPorProducto($idproducto, $desde, $hasta){
        $sql = "SELECT v.idventa, DATE_FORMAT(v.fecha,'%d/%m/%Y') fecha, v.serie, v.correlativo, tc.nombre comprobante,
                        c.nombre cliente, d.cantidad, d.unidad, d.pventa, d.total 
                FROM venta v INNER JOIN tipocomprobante tc ON v.idtipocomprobante=tc.idtipocomprobante
                INNER JOIN detalle d ON v.idventa=d.idventa
                LEFT JOIN cliente c ON v.idcliente=c.idcliente
                WHERE v.estado=1 AND d.estado=1 AND d.idproducto=:idproducto ";
        $parametros = array(':idproducto'=>$idproducto);
        if($desde!=""){
            $sql.=" AND v.fecha>=:desde ";
            $parametros[':desde']=$desde;
        }
        if($hasta!=""){
            $sql.=" AND v.fecha<=:hasta ";
            $parametros[':hasta']=$hasta;
        }

        $sql.=" ORDER BY v.fecha DESC";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;
    }

    function obtenerComprobante($id){
        $sql = "SELECT * FROM tipocomprobante WHERE idtipocomprobante=?";
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute(array($id));
        return $pre;        
    }

    function obtenerReporteVentas($desde, $hasta,$cliente){
        $sql = "SELECT YEAR(v.fecha) anio, MONTH(v.fecha) mes, SUM(v.total) total
        FROM venta v LEFT JOIN cliente c ON v.idcliente=c.idcliente
        WHERE v.estado=1 ";

        $parametros = array();
        if($desde!=""){
            $sql.=" AND v.fecha>=:desde ";
            $parametros[':desde'] = $desde;
        }
        if($hasta!=""){
            $sql.=" AND v.fecha<=:hasta ";
            $parametros[':hasta'] = $hasta;
        }
        if($cliente!="%%"){
            $sql.=" AND c.nombre LIKE :cliente ";
            $parametros[':cliente'] = $cliente;
        }

        $sql.=" GROUP BY anio, mes ORDER BY anio ASC, mes ASC ";
        
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;          
    }

    function obtenerVentasDelDia($fecha){
        $sql = "SELECT SUM(total) 
                FROM venta 
                WHERE estado=1
                AND fecha=:fecha";
        $parametros = array(":fecha"=>$fecha);
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;         
    }

    function obtenerVentasDelMes($anio, $mes){
        $sql = "SELECT SUM(total) 
                FROM venta 
                WHERE estado=1
                AND YEAR(fecha)=:anio AND MONTH(fecha)=:mes";
        $parametros = array(":anio"=>$anio, ":mes"=>$mes);
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;         
    }

    function cantidadVentasDelDia($fecha){
        $sql = "SELECT count(*) 
                FROM venta 
                WHERE estado=1
                AND fecha=:fecha";
        $parametros = array(":fecha"=>$fecha);
        global $cnx;
        $pre = $cnx->prepare($sql);
        $pre->execute($parametros);
        return $pre;         
    }

}

?>