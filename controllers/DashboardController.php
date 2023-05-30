<?php
namespace Controllers;

use Model\Proyecto;
use Model\Usuarios;
use MVC\Router;

class DashboardController{
    public static function index(Router $router){
        session_start();
        isAuth();
        $id = $_SESSION["id"];
        $proyectos = Proyecto::belongsTo("propietarioId",$id);
        
        $router->render("dashboard/index",[
            "titulo"=> "Proyectos",
            "proyectos"=>$proyectos
        ]);
    }
    public static function crear_proyecto(Router $router){
        session_start();
        isAuth();
        $alertas=[];
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $proyecto = new Proyecto($_POST);
            $alertas = $proyecto->validarProyecto();
            if(empty($alertas)){
                //crear url unico
                $hash = md5(uniqid());
                $proyecto->url = $hash;
                //asingarle el propietario al proyecto
                $proyecto->propietarioId = $_SESSION["id"];
                //guardar campo
                $proyecto->guardar();
                header("Location: /proyecto?id=".$proyecto->url);
            }
        }
        $router->render("dashboard/crear-proyecto",[
            "titulo" => "Crear Proyecto",
            "alertas"=> $alertas
        ]);
    }
    public static function proyecto(Router $router){
        session_start();
        isAuth();
        $token = $_GET["id"];
        if(!$token)header("Location: /dashboard");
        //revisar que la perdona que visita el proyecto, es quien lo creo
        $proyecto= Proyecto::where("url",$token);
        if($proyecto->propietarioId!==$_SESSION["id"]){
            header("Location: /dashboard");
        }
        $router->render("dashboard/proyecto",[
            "titulo"=> $proyecto->proyecto
        ]);
    }
    public static function perfil(Router $router){
        session_start();
        isAuth();
        $alertas=[];
        $usuario = Usuarios::find($_SESSION["id"]);
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $usuario->sincronizar($_POST);
            $alertas=$usuario->validar_usuario();
            if(empty($alertas)){
                $existeUsuario = Usuarios::where("email",$usuario->email);
                if ($existeUsuario && $existeUsuario->id!== $usuario->id) {
                    Usuarios::setAlerta("error","Email no valido, ya pertenece a otra cuenta");
                }else{
                    $usuario->guardar();
                    Usuarios::setAlerta("exito","Guardado correctamente");
                    $_SESSION["nombre"]=$usuario->nombre;
                }
                $alertas= Usuarios::getAlertas();
            }
        }
        $router->render("dashboard/perfil",[
            "titulo"=>"Perfil",
            "usuario"=> $usuario,
            "alertas"=> $alertas
        ]);
    }
    public static function cambiar_password(Router $router){
        session_start();
        isAuth();
        $alertas=[];
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $usuario = Usuarios::find($_SESSION["id"]);
            //sincronizamos con los datos del usuario mandados
            $usuario->sincronizar($_POST);
            $alertas = $usuario->password_nuevo();
            if(empty($alertas)){
                //paso la validacion
                $respuesta = $usuario->comprobar_password();
                if ($respuesta) {
                    $usuario->password = $usuario->password_nuevo;
                    //eliminar variables no necesarias
                    unset($usuario->password_nuevo);
                    unset($usuario->password_actual);
                    $usuario->hashPassword();
                    $resultado = $usuario->guardar();
                    if ($resultado) {
                        Usuarios::setAlerta("exito","Password actualizado correctamente");
                    }
                } else {
                    Usuarios::setAlerta("error","El password es incorrecto");
                }
                $alertas = Usuarios::getAlertas();
            }
        }
        $router->render("dashboard/cambiar-password",[
            "titulo"=> "Cambiar Password",
            "alertas"=> $alertas
        ]);
    }
}