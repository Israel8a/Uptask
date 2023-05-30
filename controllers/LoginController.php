<?php
namespace Controllers;

use Clases\Email;
use Model\Usuarios;
use MVC\Router;
 
class LoginController{
    public static function login(Router $router){
        $alertas=[];
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $auth =New Usuarios($_POST);
            $alertas = $auth->validarAuth();
            if(empty($alertas)){
                //veificar que usuario exista
                $usuario = Usuarios::where("email",$auth->email);
                if(!$usuario || !$usuario->confirmado=="1"){
                    Usuarios::setAlerta("error","El usuario no existe o no esta confirmado");
                }else{
                    if(password_verify($_POST["password"],$usuario->password)){
                        session_start();
                        $_SESSION["id"]=$usuario->id;
                        $_SESSION["nombre"]=$usuario->nombre;
                        $_SESSION["email"]=$usuario->email;
                        $_SESSION["login"]=true;
                        header("Location: /dashboard");
                    }else{
                        Usuarios::setAlerta("error","El Password es incorrecto");
                    }
                }
            }
        }
        $alertas = Usuarios::getAlertas();
        //Render ala vista
        $router->render("auth/login",[
            "titulo"=>"Inicia sesión",
            "alertas"=> $alertas
        ]);
    }
    public static function logout(){
        session_start();
        $_SESSION=[];
        header("Location: /");
    }
    public static function crear(Router $router){
        $usuario = new Usuarios;
        $alertas = [];
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            if(empty($alertas)){
                $existeUsuario = Usuarios::where("email",$usuario->email);
                if ($existeUsuario) {
                    Usuarios::setAlerta("error","El Usuario ya esta Registrado");
                    $alertas = Usuarios::getAlertas();
                } else {
                    //hash el password primero hicimos una variable en Usuario
                    $usuario->hashPassword();
                    //Eliminar password2
                    unset($usuario->password2);
                    //Hacemos un token primero hacemos la variable n usuarios
                    $usuario->tokenUnico();
                    //guardamos usuario
                    $resultado =$usuario->guardar();
                    $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                    $email->enviarConfirmacion();
                    //enviar email
                    if($resultado){
                        header("Location: /mensaje");
                    }
                }
                
            }
        }
        $router->render("auth/crear",[
            "titulo" => "Crea tu cuenta en UpTask",
            "usuario" => $usuario,
            "alertas" => $alertas
        ]);
    }
    public static function olvide(Router $router){
        $alertas=[];
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $usuario = new Usuarios($_POST);
            $alertas = $usuario->validarEmail();
            if(empty($alertas)){
                $usuario = Usuarios::where("email",$usuario->email);

                if($usuario && $usuario->confirmado ==="1"){
                    //generar un nuevo token
                    $usuario->tokenUnico();
                    unset($usuario->password2);
                    //Actualizar el usuario
                    $usuario->guardar();
                    //Enviar el email
                    $email = new Email($usuario->nombre,$usuario->email,$usuario->token);
                    $email->enviarInstrucciones();
                    //Imprimir la alerta
                    Usuarios::setAlerta("exito","Hemos enviado las instrucciones a tu email");
                }else{
                    Usuarios::setAlerta("error","usuario no existe o no esta confirmado");
                }
            } 
        }
        $alertas = Usuarios::getAlertas();
        $router->render("auth/olvide",[
            "titulo" => "Olvide mi Password",
            "alertas"=> $alertas
        ]);
    }
    public static function reestablecer(Router $router){
        $alertas=[];
        $token = s($_GET["token"]);
        $mostrar = true;
        if(!$token)header("Location: /");
        // identificamos el usuario con el token
        $usuario = Usuarios::where("token",$token);
        if(empty($usuario)){
            Usuarios::setAlerta("error","Token no valido");
            $mostrar = false;
        }
        $alertas = Usuarios::getAlertas();
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarPassword();
            if(empty($alertas)){
                $usuario->hashPassword();
                unset($usuario->password2);
                $usuario->token=null;
                $resultado = $usuario->guardar();
                if($resultado){
                    header("Location: /");
                }
            }
        }
        $router->render("auth/reestablecer",[
            "titulo" => "Reestablecer Password",
            "alertas" => $alertas,
            "mostrar" =>$mostrar
        ]);
    }
    public static function mensaje(Router $router){
        $router->render("auth/mensaje",[
            "titulo" => "Cuenta Creada Correctamente"
        ]);
    } 
    public static function confirmar(Router $router){
        $token = s($_GET["token"]);
        if(!$token) header("Location: /");
        $usuario = Usuarios::where("token",$token);
        if (!$usuario) {
            usuarios::setAlerta("error","token no valido");
        } else {
            $usuario->confirmado = 1;
            $usuario->token = null;
            unset($usuario->password2);
            $usuario->guardar();
            Usuarios::setAlerta("exito","Cuenta Comprobada Correctamente");
        }
        $alertas = Usuarios::getAlertas();
        $router->render("auth/confirmar",[
            "titulo" => "Confirma tu cuenta UpTask",
            "alertas"=> $alertas
        ]);
    }
}