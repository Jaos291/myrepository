<?php
session_start();
//Variable de SESION para usar de forma global y evitar el acceso a principal.php sin hacer login
$_SESSION['autorizado'] = false;
$autorizado =false;
$email ="";
$password ="";
$msg ="";

//validar los campos email y password con _POST de la sección html
if (isset($_POST['email']) && isset($_POST['password'])) {
  if ($_POST['email'] =="") {
    $msg.="Ingrese un correo <br>";
  } else if ($_POST['password'] == ""){
      $msg.="Ingrese su contraseña <br>";
  }else{

    //quitar los posibles html tags que se puedan ingresar por error
  $email = strip_tags($_POST['email']);
  $password = sha1(strip_tags($_POST['password']));

  //creamos la instancia de conexión con la base de datos
  $mysqli = mysqli_connect("localhost","root", "", "pruebaphp");
  if (!$mysqli) {
    echo"Error en la conexión";
    die();
  }
  //creamos el query MySQL que debe ejecutarse en esta parte del codigo
  $resultado = $mysqli->query("SELECT * FROM `usuarios`  WHERE `usuarios_correo` = '".$email."' AND `usuarios_password` = '".$password."' ");

  //asignamos a una variable el resultado del query en forma de Array
  $usuarios = $resultado->fetch_all(MYSQLI_ASSOC);
  /**echo"<pre>";
  print_r($usuarios);
  die();**/
  //contamos si hay algo dentro de la variable $usuarios
  $cantidad = count($usuarios);
 //Creamos variables de sesion para manejar la información del usuario de manera constante
 //Debido a que el array generado es multidimensional, se especifica que es la posición [0] y las diferentes columnas que necesitemos (usuarios_id, usuarios_email, usuarios_ultimo_login)
  $_SESSION['usuarios_id'] = $usuarios[0]['usuarios_id'];
  $_SESSION['usuarios_email'] = $usuarios[0]['usuarios_correo'];
  $_SESSION['usuarios_ultimo_login'] = $usuarios[0]['usuarios_ultimo_login'];

  //Si existe quiere decir que si encontró el usuario con SELECT
  if ($cantidad == 1) {
    $hoy = date("Y-m-d H:i:s");
    $mysqli->query("UPDATE `usuarios` SET `usuarios_ultimo_login`= '".$hoy."' WHERE `usuarios_email` = '".$email."'");
    $msg ="Bienvenido";
    //Hasta este punto el usuario ha podido ingresar, entonces está autorizado
    $_SESSION['autorizado'] = true;
    echo '<meta http-equiv="refresh" content="1; url=principal.php">';
      }else{
    $msg.="Usuario inválido o contraseña inválido <br>";
    $_SESSION['autorizado'] = false;
  }
}
}
 ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>PHP Tube</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <b>Prueba</b> PHP
  </div>
  <!-- /.login-logo -->
  <div class="card">
    <div class="card-body login-card-body">
      <p class="login-box-msg">Inicia sesión</p>

      <form action="login.php" method="post">
        <div class="input-group mb-3">
          <input name = "email" type="email" class="form-control" placeholder="Email o Usuario" value = <?php echo $email ?>>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="password" type="password" class="form-control" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <label>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Ingresar</button>
          </div>
          <!-- /.col -->
          <div style="color:red">
              <?php echo $msg; ?>
          </div>
        </div>
      </form>

      <p class="mb-1">
        <a href="#">Olvidé mi contraseña</a>
      </p>
      <p class="mb-0">
        <a href="register.php" class="text-center">Registrar una cuenta</a>
      </p>
    </div>
    <!-- /.login-card-body -->
  </div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.min.js"></script>

</body>
</html>
