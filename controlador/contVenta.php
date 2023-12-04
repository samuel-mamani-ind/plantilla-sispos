<?php
require_once('../modelo/clsVenta.php');
require_once('../modelo/clsProducto.php');
require_once('../modelo/clsCliente.php');

controlador($_POST['accion']);
function controlador($accion){

	$objVen = new clsVenta();
	$objPro = new clsProducto();
	$objClie = new clsCliente();

	switch($accion){
        case "NUEVO":
            try{
                $idventa = 0;
                $correlativo = $objVen->obtenerCorrelativo($_POST['idtipocomprobante1'],$_POST['serie1']);
                $duplicado = $objVen->consultarVentaExistente($_POST['idtipocomprobante1'],$_POST['serie1'],$correlativo);
                    
                $carrito = array();
                if(isset($_SESSION['carrito'])){
                    $carrito = $_SESSION['carrito'];
                }
                $codigoError = "";
                $problemasStock = array();
                //inicio de validaciones de stock de productos
                foreach($carrito as $k=>$v){
                    $producto = $objPro->consultarProducto($v["idproducto"]);
                    $producto = $producto->fetch(PDO::FETCH_NAMED);
                    
                    if($v['cantidad']>$producto['stock']){
                        $v['stockdisponible'] = $producto['stock'];
                        $problemasStock[]=$v;
                    }
                }
                //fin de validaciones de productos

                if($duplicado->rowCount()==0 && count($problemasStock)==0){
                                        
                    $idcliente = 0;
                    $existe = $objClie->verificarDuplicado($_POST['nrodocumento']);
                    if($existe->rowCount()>0){
                        $cliente = $existe->fetch(PDO::FETCH_NAMED);
                        $idcliente = $cliente['idcliente'];
                    }else if($_POST['nrodocumento']!=""){
                        $objClie->insertarCliente($_POST['nombre'],$_POST['idtipodocumento'],$_POST['nrodocumento'],$_POST['direccion'],1);
                        $existe = $objClie->verificarDuplicado($_POST['nrodocumento']);
                        if($existe->rowCount()>0){
                            $cliente = $existe->fetch(PDO::FETCH_NAMED);
                            $idcliente = $cliente['idcliente'];
                        }
                    }                    

                    $opGravadas = 0;
                    $total_igv = 0;
                    $opExoneradas = 0;
                    $opInafectas  = 0;
                    $total_icbper  = 0;
                    $total_descuentos  = 0;    
                    
                    foreach($carrito as $k=>$v){
                        $rowspan=1;
                        if(isset($v['descuento']) && $v['descuento']>0){
                            $rowspan=2;
                            $total_descuentos = $total_descuentos +  $v['descuento'];
                        }
                        $subtotal = $v['cantidad']*$v['pventa'];
                        $igv = 0.18;
                        $icbper = 0.50;
                        $valortotal = $subtotal;
                        $carrito[$k]["icbper"] = 0;
                        if($v['afectoicbper']==1){
                            $valortotal = $valortotal - $icbper*$v['cantidad'];
                            $total_icbper = $total_icbper + $icbper*$v['cantidad'];
                            $carrito[$k]["icbper"] =  $icbper*$v['cantidad'];
                        }

                        $carrito[$k]["igv"] = 0;
                        if($v['afectacion']==10){
                            $valortotal_calculado = round($valortotal/(1+$igv),2);
                            $opGravadas = $opGravadas + $valortotal_calculado;
                            $total_igv = $total_igv +  ($valortotal - $valortotal_calculado);
                            $carrito[$k]["igv"] = $valortotal - $valortotal_calculado;
                        }else if($v['afectacion']==20){
                            $opExoneradas = $opExoneradas + $valortotal;
                        }else if($v['afectacion']==30){
                            $opInafectas = $opInafectas + $valortotal; 
                        }
                    }

                    $totalFinal = $opGravadas + $opInafectas + $opExoneradas 
                                + $total_igv + $total_icbper - $total_descuentos;


                    $venta = array();

                    $venta["fecha"]             = $_POST["fecha"];              
                    $venta["idcliente"]         = $idcliente;         
                    $venta["idtipocomprobante"] = $_POST["idtipocomprobante1"]; 
                    $venta["serie"]             = $_POST["serie1"];             
                    $venta["correlativo"]       = $correlativo;       
                    $venta["total"]             = $totalFinal;             
                    $venta["total_gravado"]     = $opGravadas;     
                    $venta["total_exonerado"]   = $opExoneradas;   
                    $venta["total_inafecto"]    = $opInafectas;    
                    $venta["total_igv"]         = $total_igv;         
                    $venta["total_icbper"]      = $total_icbper;      
                    $venta["total_descuento"]   = $total_descuentos;   
                    $venta["formapago"]         = $_POST["formapago"];        
                    $venta["idmoneda"]          = $_POST["moneda"];          
                    $venta["vencimiento"]       = $_POST["vencimiento"]==''?NULL:$_POST["vencimiento"];       
                    $venta["guiaremision"]      = $_POST["guiaremision"];      
                    $venta["ordencompra"]       = $_POST["ordencompra"];       

                    $resultado = $objVen->insertar($venta);   
                    $objVen->actualizarCorrelativo($_POST['idtipocomprobante1'], $_POST['serie1'], $correlativo);

                    $venta = $objVen->consultarVentaExistente($_POST['idtipocomprobante1'],$_POST['serie1'],$correlativo);                    
                    if($venta->rowCount()>0){

                        $venta = $venta->fetch(PDO::FETCH_NAMED);
                        $idventa = $venta["idventa"];
                        $detalle_venta = array();
                        foreach($carrito as $k=>$v){                            
                            $detalle = array();
                            $detalle["idventa"]       = $venta["idventa"];
                            $detalle["idproducto"]    = $v["idproducto"];    
                            $detalle["cantidad"]      = $v["cantidad"];
                            $detalle["unidad"]        = $v["unidad"];
                            $detalle["pventa"]        = $v["pventa"];
                            $detalle["igv"]           = $v["igv"];
                            $detalle["icbper"]        = $v["icbper"];
                            $detalle["descuento"]     = $v["descuento"];
                            $detalle["total"]         =($v["pventa"]*$v["cantidad"])-$v["descuento"];
                            $detalle["idafectacion"]  = $v["afectacion"];    
                            $detalle_venta[] = $detalle;
                        }                        
                        $objVen->insertarDetalle($detalle_venta);
                        //actualizamos Stock
                        foreach($carrito as $k=>$v){
                            $objPro->actualizarStock($v["idproducto"],$v["cantidad"]*-1);
                        }
                        //fin actualizacion de stock
                    }
                    $codigoError =  1;
                }else if(count($problemasStock)>0){
                    $codigoError = 99;
                }else{
                    $codigoError = 2;
                }
            }catch(Exception $ex){
                $codigoError = 0;
            }
            $array_retorno = array("idventa"=>$idventa,"codigoError"=>$codigoError, "problemasStock"=>$problemasStock); 
            echo json_encode($array_retorno);
            break;

        case "ACTUALIZAR":
            try{
                $duplicado = $objVen->consultarVentaExistente($_POST['idtipocomprobante1'],$_POST['serie1'],$_POST['correlativo'], $_POST['idventa']);

                $carrito = array();
                if(isset($_SESSION['carrito'])){
                    $carrito = $_SESSION['carrito'];
                }  

                $codigoError = "";
                $problemasStock = array();
                //inicio de validaciones de stock de productos
                $detalle_venta_old = $objVen->consultarDetalleVenta($_POST['idventa']);
                $detalle_venta_old = $detalle_venta_old->fetchAll(PDO::FETCH_NAMED); 
                foreach($carrito as $k=>$v){
                    $producto = $objPro->consultarProducto($v["idproducto"]);
                    $producto = $producto->fetch(PDO::FETCH_NAMED);
                    foreach($detalle_venta_old as $p=>$q){
                        if($q['idproducto']==$v['idproducto']){
                            $producto['stock'] = $producto['stock'] + $q['cantidad'];
                        }
                    }
                    if($v['cantidad']>$producto['stock']){
                        $v['stockdisponible'] = $producto['stock'];
                        $problemasStock[]=$v;
                    }
                }
                //fin de validaciones de productos

                if($duplicado->rowCount()==0 && count($problemasStock)==0){
                    $idcliente = 0;
                    $existe = $objClie->verificarDuplicado($_POST['nrodocumento']);
                    if($existe->rowCount()>0){
                        $cliente = $existe->fetch(PDO::FETCH_NAMED);
                        $idcliente = $cliente['idcliente'];
                    }else if($_POST['nrodocumento']!=""){
                        $objClie->insertarCliente($_POST['nombre'],$_POST['idtipodocumento'],$_POST['nrodocumento'],$_POST['direccion'],1);
                        $existe = $objClie->verificarDuplicado($_POST['nrodocumento']);
                        if($existe->rowCount()>0){
                            $cliente = $existe->fetch(PDO::FETCH_NAMED);
                            $idcliente = $cliente['idcliente'];
                        }
                    }                    

                    $opGravadas = 0;
                    $total_igv = 0;
                    $opExoneradas = 0;
                    $opInafectas  = 0;
                    $total_icbper  = 0;
                    $total_descuentos  = 0;  
                    
                    foreach($carrito as $k=>$v){
                        $rowspan=1;
                        if(isset($v['descuento']) && $v['descuento']>0){
                            $rowspan=2;
                            $total_descuentos = $total_descuentos +  $v['descuento'];
                        }
                        $subtotal = $v['cantidad']*$v['pventa'];
                        $igv = 0.18;
                        $icbper = 0.50;
                        $valortotal = $subtotal;
                        $carrito[$k]["icbper"] = 0;
                        if($v['afectoicbper']==1){
                            $valortotal = $valortotal - $icbper*$v['cantidad'];
                            $total_icbper = $total_icbper + $icbper*$v['cantidad'];
                            $carrito[$k]["icbper"] =  $icbper*$v['cantidad'];
                        }

                        $carrito[$k]["igv"] = 0;
                        if($v['afectacion']==10){
                            $valortotal_calculado = round($valortotal/(1+$igv),2);
                            $opGravadas = $opGravadas + $valortotal_calculado;
                            $total_igv = $total_igv +  ($valortotal - $valortotal_calculado);
                            $carrito[$k]["igv"] = $valortotal - $valortotal_calculado;
                        }else if($v['afectacion']==20){
                            $opExoneradas = $opExoneradas + $valortotal;
                        }else if($v['afectacion']==30){
                            $opInafectas = $opInafectas + $valortotal; 
                        }
                    }

                    $totalFinal = $opGravadas + $opInafectas + $opExoneradas 
                                + $total_igv + $total_icbper - $total_descuentos;


                    $venta = array();
                    $venta["idventa"]           = $_POST["idventa"];
                    $venta["fecha"]             = $_POST["fecha"];              
                    $venta["idcliente"]         = $idcliente;         
                    $venta["idtipocomprobante"] = $_POST["idtipocomprobante1"]; 
                    $venta["serie"]             = $_POST["serie1"];             
                    $venta["correlativo"]       = $_POST["correlativo"];       
                    $venta["total"]             = $totalFinal;             
                    $venta["total_gravado"]     = $opGravadas;     
                    $venta["total_exonerado"]   = $opExoneradas;   
                    $venta["total_inafecto"]    = $opInafectas;    
                    $venta["total_igv"]         = $total_igv;         
                    $venta["total_icbper"]      = $total_icbper;      
                    $venta["total_descuento"]   = $total_descuentos;   
                    $venta["formapago"]         = $_POST["formapago"];        
                    $venta["idmoneda"]          = $_POST["moneda"];          
                    $venta["vencimiento"]       = $_POST["vencimiento"]==""?NULL:$_POST["vencimiento"];       
                    $venta["guiaremision"]      = $_POST["guiaremision"];      
                    $venta["ordencompra"]       = $_POST["ordencompra"];
                    $venta["estado"]            = 1;       

                    $resultado = $objVen->actualizar($venta);   

                    $objVen->cambiarEstadoDetalle($_POST['idventa'],2);
                    //actualizamos stock antiguo
                    foreach($detalle_venta_old as $k=>$v){
                        $objPro->actualizarStock($v["idproducto"],$v["cantidad"]);
                    }
                    //fin de actualización de stock antiguo

                    $detalle_venta = array();
                    foreach($carrito as $k=>$v){                            
                        $detalle = array();
                        $detalle["idventa"]       = $_POST['idventa'];
                        $detalle["idproducto"]    = $v["idproducto"];    
                        $detalle["cantidad"]      = $v["cantidad"];
                        $detalle["unidad"]        = $v["unidad"];
                        $detalle["pventa"]        = $v["pventa"];
                        $detalle["igv"]           = $v["igv"];
                        $detalle["icbper"]        = $v["icbper"];
                        $detalle["descuento"]     = $v["descuento"];
                        $detalle["total"]         =($v["pventa"]*$v["cantidad"])-$v["descuento"];
                        $detalle["idafectacion"]  = $v["afectacion"];    
                        $detalle_venta[] = $detalle;
                    }                        
                    $objVen->insertarDetalle($detalle_venta);
                    //actualizamos Stock actual
                    foreach($carrito as $k=>$v){
                        $objPro->actualizarStock($v["idproducto"],$v["cantidad"]*-1);
                    }
                    //fin actualizacion de stock actual                    
                    $codigoError =  1;
                }else if(count($problemasStock)>0){
                    $codigoError = 99;
                }else{
                    $codigoError = 2;
                }
            }catch(Exception $ex){
                $codigoError = 0;
            }
            $array_retorno = array("idventa"=>$_POST['idventa'],"codigoError"=>$codigoError, "problemasStock"=>$problemasStock); 
            echo json_encode($array_retorno);
            break;


        case "ELIMINAR":
            try{
                $resultado = $objVen->actualizarEstado($_POST['idventa'],2);
                
                $detalle_venta_old = $objVen->consultarDetalleVenta($_POST['idventa']);
                $resultado = $objVen->cambiarEstadoDetalle($_POST['idventa'],2);

                foreach($detalle_venta_old as $k=>$v){
                    if($v['estado']==1){
                        $objPro->actualizarStock($v["idproducto"],$v["cantidad"]);
                    }
                }

                echo 1;
            }catch(Exception $ex){
                echo 0;
            }
            break;

        case "ANULAR":
            try{
                $resultado = $objVen->actualizarEstado($_POST['idventa'],0);

                $detalle_venta_old = $objVen->consultarDetalleVenta($_POST['idventa']);

                 $resultado = $objVen->cambiarEstadoDetalle($_POST['idventa'],0);

                foreach($detalle_venta_old as $k=>$v){
                    $objPro->actualizarStock($v["idproducto"],$v["cantidad"]);                    
                }

                echo 1;
            }catch(Exception $ex){
                echo 0;
            }
            break;


        case "ACTIVAR":
            try{
                $detalle_venta_old = $objVen->consultarDetalleVenta($_POST['idventa']);
                $detalle_venta_old = $detalle_venta_old->fetchAll(PDO::FETCH_NAMED);
                $existeProblemasStock = false;
                $mensaje = "";
                foreach($detalle_venta_old as $k=>$v){
                    $producto = $objPro->consultarProducto($v['idproducto']);
                    $producto = $producto->fetch(PDO::FETCH_NAMED);
                    if($v['cantidad']>$producto['stock']){
                        $existeProblemasStock = false;
                        $mensaje.="<br/>".$producto['nombre'];
                    }
                }

                $resultado = $objVen->actualizarEstado($_POST['idventa'],1);
                $resultado = $objVen->cambiarEstadoDetalle($_POST['idventa'],1);            

                foreach($detalle_venta_old as $k=>$v){
                    $objPro->actualizarStock($v["idproducto"],$v["cantidad"]*-1);
                }                
                if($existeProblemasStock){
                    echo "Existe problemas de stock con los productos:".$mensaje;
                }else{
                    echo 1;
                }
            }catch(Exception $ex){
                echo 0;
            }
            break;

        case "CONSULTAR":
            $retorno = array();
            try{
                $resultado = $objVen->consultarVenta($_POST['idventa']);
                if($resultado->rowCount()>0){
                    $retorno = $resultado->fetch(PDO::FETCH_NAMED);
                }
            }catch(Exception $ex){
                $retorno = array();
            }
            echo json_encode($retorno);
            break; 

        case "SERIES":
            $retorno = array();
            try{
                $resultado = $objVen->consultarSeries($_POST['idtipocomprobante']);
                $retorno = $resultado->fetchAll(PDO::FETCH_NAMED);            
            }catch(Exception $ex){
                $retorno = array();
            }
            echo json_encode($retorno);
            break; 

        case "OBTENER_CORRELATIVO":
            try{
                $correlativo = $objVen->obtenerCorrelativo($_POST['idtipocomprobante'],$_POST['serie']);
                echo $correlativo;
            }catch(Exception $ex){
                echo 0;
            }
            break;

        case "AGREGAR_PRODUCTO":
            try{
                $idproducto = $_POST['idproducto'];
                $producto = $objPro->consultarProducto($idproducto);
                if($producto->rowCount()>0){
                    $producto = $producto->fetch(PDO::FETCH_NAMED);                    
                }

                $carrito = array();
                if(isset($_SESSION['carrito'])){
                    $carrito = $_SESSION['carrito'];
                }

                $agregar = true;
                foreach($carrito as $k=>$v){
                    if($v['idproducto']==$producto['idproducto']){
                        $carrito[$k]["cantidad"]++;
                        $agregar = false;
                    }
                }

                if($agregar){
                    $itempos = count($carrito)+1; 
                    $item = array(
                        "item"          =>$itempos,
                        "codigo"        => $producto["codigobarra"],
                        "idproducto"    => $producto["idproducto"],
                        "nombre"        => $producto["nombre"],
                        "pventa"        => $producto["pventa"],
                        "cantidad"      => 1,
                        "descuento"      => 0,
                        "afectacion"    =>$producto["idafectacion"],
                        "afectoicbper"  =>$producto["afectoicbper"],
                        "unidad"        =>$producto["idunidad"]
                    );
                    $carrito[$itempos] = $item;
                }

                $_SESSION['carrito'] = $carrito;                
            }catch(Exception $ex) {
               echo "Error al agregar producto";
            }
            break;

            case "VER_CARRITO":
                try{

                    if(isset($_POST['idventa']) && $_POST['idventa']>0){
                        $detalle_venta = $objVen->consultarDetalleVenta($_POST['idventa']);
                        $carrito_old = array();
                        $itempos=1;
                        foreach($detalle_venta as $k=>$v){
                            $producto = $objPro->consultarProducto($v["idproducto"]);
                            $producto = $producto->fetch(PDO::FETCH_NAMED);
                            $carrito_old[$itempos] = array(
                                "item"          =>$itempos,
                                "codigo"        => $producto["codigobarra"],
                                "idproducto"    => $v["idproducto"],
                                "nombre"        => $producto["nombre"],
                                "pventa"        => $v["pventa"],
                                "cantidad"      => $v["cantidad"],
                                "descuento"      => $v["descuento"],
                                "afectacion"    =>$v["idafectacion"],
                                "afectoicbper"  =>$v["icbper"]>0?1:0,
                                "unidad"        =>$v['unidad']
                            ); 
                            $itempos++;
                        }
                        $_SESSION["carrito"] = $carrito_old;
                    }

                    $opGravadas = 0;
                    $total_igv = 0;
                    $opExoneradas = 0;
                    $opInafectas  = 0;
                    $total_icbper  = 0;
                    $total_descuentos  = 0;

                    $carrito = array();
                    if(isset($_SESSION['carrito'])){
                        $carrito = $_SESSION['carrito'];
                    }    
                    //DIBUJAR LA VISUALIZACION DEL CARRITO
                    
                    $contenido = "<table class='table table-sm text-sm table-bordered table-hover'>
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Producto</th>
                                            <th>Cantidad</th>
                                            <th>PV</th>
                                            <th>Sub</th>
                                            <th>*</th>
                                            <th>*</th>
                                        </tr>
                                    </thead><tbody>";
                    foreach($carrito as $k=>$v){
                        $rowspan=1;
                        if(isset($v['descuento']) && $v['descuento']>0){
                            $rowspan=2;
                            $total_descuentos = $total_descuentos +  $v['descuento'];
                        }
                        $subtotal = $v['cantidad']*$v['pventa'];
                        $igv = 0.18;
                        $icbper = 0.50;
                        $valortotal = $subtotal;
                        if($v['afectoicbper']==1){
                            $valortotal = $valortotal - $icbper*$v['cantidad'];
                            $total_icbper = $total_icbper + $icbper*$v['cantidad'];
                        }

                        if($v['afectacion']==10){
                            $valortotal_calculado = round($valortotal/(1+$igv),2);
                            $opGravadas = $opGravadas + $valortotal_calculado;
                            $total_igv = $total_igv +  ($valortotal - $valortotal_calculado);
                        }else if($v['afectacion']==20){
                            $opExoneradas = $opExoneradas + $valortotal;
                        }else if($v['afectacion']==30){
                            $opInafectas = $opInafectas + $valortotal; 
                        }

                            $contenido.="<tr id='trcarrito".$k."'>";
                            $contenido.="<td rowspan='".$rowspan."'>".$k."</td>";
                            $contenido.="<td>".$v['nombre']."</td>";
                            $contenido.="<td>".((float) $v['cantidad'])."</td>";
                            $contenido.="<td>".number_format($v['pventa'],2)."</td>";
                            $contenido.="<td>".number_format($subtotal,2)."</td>";
                            $contenido.="<td><button type='button' class='btn btn-xs bg-green' onclick='EditarItem(".$k.")'><span class='fas fa-edit'></span></button></td>";
                            $contenido.="<td><button type='button' class='btn btn-xs bg-red' onclick='EliminarItem(".$k.")'><span class='fas fa-trash'></span></button></td>";
                            $contenido.="</tr>";
                            if(isset($v['descuento']) && $v['descuento']>0){
                                $contenido.="<tr>";
                                $contenido.="<td>Descuento</td>";
                                $contenido.="<td></td>";
                                $contenido.="<td></td>";
                                $contenido.="<td>".number_format(($v['descuento']*-1),2)."</td>";
                                $contenido.="<td></td>";
                                $contenido.="<td></td>";
                                $contenido.="</tr>";                                
                            }
                    }
                    $contenido .= "</tbody></table>";

                    $totalFinal = $opGravadas + $opInafectas + $opExoneradas 
                                + $total_igv + $total_icbper - $total_descuentos;

                    $contenido.= "<div align='right'><table>";
                    $contenido.= "<tr><td align='right'>Op. Gravadas:&nbsp;</td><td>".number_format($opGravadas,2)."</td></tr>";
                    $contenido.= "<tr><td align='right'>IGV:&nbsp;</td><td>".number_format($total_igv,2)."</td></tr>";
                    $contenido.= "<tr><td align='right'>Op. Exoneradas:&nbsp;</td><td>".number_format($opExoneradas,2)."</td></tr>";
                    $contenido.= "<tr><td align='right'>Op. Inafectas:&nbsp;</td><td>".number_format($opInafectas,2)."</td></tr>";
                    $contenido.= "<tr><td align='right'>ICBPER:&nbsp;</td><td>".number_format($total_icbper,2)."</td></tr>";
                    $contenido.= "<tr><td align='right'>Total Descuento:&nbsp;</td><td>".number_format($total_descuentos*-1,2)."</td></tr>";
                    $contenido.= "<tr><td align='right'>Total:&nbsp;</td><td>".number_format($totalFinal,2)."</td></tr>";
                    $contenido.= "</table></div>";
                    $contenido.="<input type='text' style='display: none' name='txtTotalVenta' id='txtTotalVenta' value='".$totalFinal."' />";

                    echo $contenido;
                }catch(Exception $ex) {
                   echo "Error al ver carrito";
                }
                break;  
                
            case "ELIMINAR_CARRITO":
                try {
                    $_SESSION['carrito'] = array();
                } catch (Exception $ex) {
                    //nada
                }
                break;
            case "ACTUALIZAR_ITEM":
                try {
                    $item = $_POST['item'];
                    $_SESSION['carrito'][$item]['cantidad']=$_POST['cantidad'];
                    $_SESSION['carrito'][$item]['pventa']=$_POST['pventa'];
                    $_SESSION['carrito'][$item]['descuento']=$_POST['descuento'];
                } catch (Exception $ex) {
                    //nada
                }
                break;  
            case "ELIMINAR_ITEM":
                try{
                    $item = $_POST['item'];
                    $carrito = $_SESSION['carrito'];
                    for($i=$item; $i<count($carrito);$i++){
                        $carrito[$i]=$carrito[$i+1];
                    }
                    unset($carrito[count($carrito)]);
                    $_SESSION['carrito'] = $carrito;
                }catch(Exception $ex){
                    //nada
                }  
                break; 
            case "OBTENER_ITEM":
                try{
                    $item = $_POST['item'];
                    $codigo=$_SESSION['carrito'][$item]['codigo'];
                    $nombre=$_SESSION['carrito'][$item]['nombre'];
                    $pventa=$_SESSION['carrito'][$item]['pventa'];
                    $cantidad=$_SESSION['carrito'][$item]['cantidad'];
                    $descuento=$_SESSION['carrito'][$item]['descuento'];

                    $producto = $objPro->consultarProducto($_SESSION['carrito'][$item]["idproducto"]);
                    $producto = $producto->fetch(PDO::FETCH_NAMED);

                    $retorno = array(
                                "codigo" => $codigo,
                                "nombre" => $nombre,
                                "pventa" => $pventa,
                                "cantidad" => $cantidad,
                                "descuento" => $descuento,
                                "stock" => $producto['stock']
                    );
                    echo json_encode($retorno);
                }catch(Exception $ex){
                    //nada
                }  
                break;

        default:
            echo "No se ha definido proceso";
            break;                                  
    }

}

?>