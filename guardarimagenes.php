
<?php
    
    $contenido='';
    if($_SERVER['REQUEST_METHOD'] == 'GET'){ 
        $contenido= "<form name='f1' enctype='multipart/form-data' method='POST'>
                     <label>Elija el archivo a subir</label> 
                     <input name='archivos[]' type='file' multiple='true'/> <br />
                     <input type='submit' value='Subir archivo'/>
                     </form>";
    }else{ 
    
     $codigosErrorSubida=[
         0 => 'Subida correcta',
         1 => 'El tamaño del archivo excede el admitido por el servidor', 
         2 => 'El tamaño del archivo excede el admitido por el cliente', 
         3 => 'El archivo no se pudo subir completamente',
         4 => 'No se seleccionó ningún archivo para ser subido',
         6 => 'No existe un directorio temporal donde subir el archivo',
         7 => 'No se pudo guardar el archivo en disco', 
         8 => 'Una extensión PHP evito la subida del archivo'
        ];
     
     $mensaje = '';
     
     $tamanotot=0;
     $tipook=0;
     $tamanook=0; 
     $ok1=0;
     $ok2=0;
        
     $directorioSubida='C:/imgusers/';
        
      $contadorfiles=count( $_FILES['archivos']['name']);
     //controlamos que solo se suban dos archivos
        if($contadorfiles<=2){ 
            //comprobamos tamaño//
        for($i=0;$i<$contadorfiles;$i++){
           $tamanioFichero= $_FILES['archivos']['size'][$i];
            $tamanotot=$tamanotot+$tamanioFichero/1024;
           //si tiene menos de 200...//
            if(($tamanioFichero/1024)<200){
              $tamanook++;    
            }
            //si es un archivo "iamgen"//
            if($_FILES['archivos']['type'][$i]=="image/jpeg" || $_FILES['archivos']['type'][$i]=="image/png"){
                $tipook++;
            }
        }
        //si el tipo=2 ,los dos son imagenes//
        if($tipook==$contadorfiles){
            $ok1=1;
        }
        //si el tamaño=2 ,los dos tienen menos de 200
        if($tamanook==$contadorfiles){
            $ok2=1;
        }
    
        if($ok2==1){
            
            if($ok1==1){
               //si  se cumplen las dos condiciones y el tamaño es menor de 300 
                if($tamanotot<=300){
                    for( $i=0;$i<$contadorfiles;$i++){
                    $nombreFichero = $_FILES['archivos']['name'][$i];
                    $tipoFichero = $_FILES['archivos']['type'][$i];
                    $tamanioFichero = $_FILES['archivos']['size'][$i];
                    $temporalFichero = $_FILES['archivos']['tmp_name'][$i];
                    $errorFichero = $_FILES['archivos']['error'][$i];
                    $mensaje .= 'Intentando subir el archivo: ' . ' <br />';
                    $mensaje .= "- Nombre: $nombreFichero" . ' <br />';
                    $mensaje .= '- Tamaño: ' . ($tamanioFichero / 1024) . ' KB <br />';
                    $mensaje .= "- Tipo: $tipoFichero" . ' <br />' ;
                    $mensaje .= "- Nombre archivo temporal: $temporalFichero" . ' <br />';
                    $mensaje .= "- Código de estado: $errorFichero" . ' <br />';
                    $mensaje .= '<br />RESULTADO<br />';
          
                      
	                    if ($errorFichero > 0) {
	                        $mensaje .= "Se a producido el error: $errorFichero:"
	                        . $codigosErrorSubida[$errorFichero] . ' <br>';
	                    }else{ 
	                        if(is_dir($directorioSubida) && is_writable($directorioSubida)) {
	                            //Comprobamos si existe o no ya el fichero.
	                            if (file_exists($directorioSubida .'/'. $nombreFichero)) {
	                                $mensaje .= "El fichero $nombreFichero ya existe.error,no se ha subido.<br>";
                                }
	                            else{
	                                //Movemos el archivo  al directorio q proporcionamos
	                                if (move_uploaded_file($temporalFichero,  $directorioSubida .'/'. $nombreFichero) == true){
	                                    $mensaje .= 'Archivo guardado en: ' . $directorioSubida .'/'. $nombreFichero . ' <br><br>';
	                                }else{
	                                    $mensaje .= 'ERROR: Archivo no guardado correctamente. <br>';
	                                }
	                            }
	                        }
	                        else {
	                            $mensaje .= 'ERROR: No es un directorio correcto o no se tiene permiso de escritura. <br>';
	                        }
	                    }
	                }
	                echo $mensaje;
	            }
	          //tamaño de 300  excedido
	            else{
	                echo "El tamaño de ambos ficheros se excede del limite de subida(300).";
	            }
                //si es =1 el primer fichero no es imagen,else,el segundo no es
	         }else{
                if($contadorfiles==1){
                    echo 'el fichero no es  ni png ni jpeg';
                }else{
                    echo 'alguno de los fichero no es imagen';
                }
            }
        //si tiene mas de 200...
        }else{
            if($contadorfiles==1){
                echo 'el fichero excede de 200';
            }else{
                echo 'alguno de los ficheros excede 200';
            }
        }
            //si  contadorfiles es mayor q 2,si se suban mas de dos  ficheros
       }else{
            echo 'no se pueden subir mas de dos archivos';
        }       
     }
    

?>
    <html><head><title>Entrada.php</title> </head><body><?php echo $contenido; ?></body></html>