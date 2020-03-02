<?php
$mysqli = mysqli_connect("localhost","root", "", "phptube");
if (!$mysqli) {
  echo"Error en la conexión";
  die();
}

function graba_imagen($archivo){
  $mysqli = $GLOBALS['mysqli'];

  $msg = "";

  $target_dir = "archivos/";
  $target_file = $target_dir.basename($archivo["archivo"]["name"]);
  $upload = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  if (isset($_POST["submit"])) {
    $check = getimagesize($archivo["archivo"]["tmp_name"]);
    if ($check!==false) {
      $upload = 1;
    }else {
      $msg .= "El archivo no es una imagen <br>";
      $upload = 0;
    }
  }
  if (file_exists($target_file)) {
    $msg .= "La imagen ya existe <br>";
    $upload = 0;
  }
  if ($archivo["archivo"]["size"] > 50000) {
    $msg .= "Tamaño de archivo muy grande <br>";
    $upload = 0;
  }

  if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" ) {
    $msg .= "Solo se permiten archivos con extensión jgp, png o jpeg <br>";
    $upload = 0;
  }

  if ($upload == 0) {
    $msg .= "La imagen no pudo ser cargada";
  }else{
    if (move_uploaded_file($archivo["archivo"]["tmp_name"], $target_file)) {
      $msg .= "La imagen " .basename($archivo["archivo"]["name"])." ha sido subida <br>";
      $mysqli->query("UPDATE `usuarios` SET `usuarios_imagen` = '".$target_file."' WHERE `usuarios_id` = '".$_SESSION['usuarios_id']."' ");
    }else {
      $msg .= "Error no controlado <br>";
    }
  }
  return $msg;

}

  function obtener_imagen_usuario(){
    //traemos la conexión global a un ámbito local
    $mysqli = $GLOBALS['mysqli'];


    $consulta = "SELECT `usuarios_imagen` FROM `usuarios` WHERE `usuarios_id` = '".$_SESSION['usuarios_id']."'";
    $resultado = $mysqli->query($consulta);
    $fila = $resultado->fetch_assoc();

    $ruta = $fila['usuarios_imagen'];
    return $ruta;
  }

 ?>
