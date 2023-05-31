<?php
namespace Controllers;

use Model\Proyecto;
use Model\Tarea;

//importante que el nombre de la clase sea igual que al nombre de la carpeta
class TareaController{
    public static function index(){
        $proyectoId = $_GET["id"];
        if(!$proyectoId)header("Location :/dashboard");
        $proyecto = Proyecto::where("url",$proyectoId);
        session_start();
        if(!$proyecto||$proyecto->propietarioId!==$_SESSION["id"])header("Location: /404");
        $tareas = Tarea::belongsTo("proyectoId",$proyecto->id);
        echo json_encode(["tareas"=>$tareas]);
    }
    public static function crear(){
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            session_start();
            $proyectoId = $_POST["proyectoId"];
            $proyecto = Proyecto::where("url",$proyectoId);
            if(!$proyecto|| $proyecto->propietarioId !==$_SESSION["id"]){
                $respuesta=[
                    "tipo"=> "error",
                    "mensaje"=> "Hubo un Error al agregar la tarea"
                ];
                echo json_encode($respuesta);
                return;
            }
            //paso la validacion y creamos la tarea
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            $respuesta=[
                "tipo"=> "exito",
                "id" => $resultado["id"],
                "mensaje"=> "Tarea Creada correctamente",
                "proyectoId"=> $proyecto->id
            ];
            echo json_encode($respuesta);

            //con este codigo resivimos los datos de javascript a php
            //echo json_encode($respuesta);
        }
    }
    public static function actualizar(){
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $proyecto = Proyecto::where("url",$_POST["proyectoId"]);
            session_start();
            if(!$proyecto||$proyecto->propietarioId!== $_SESSION["id"]){
                $respuesta=[
                    "tipo"=>"error",
                    "mensaje"=> "Hubo un Error al actualizar la tarea"
                ];
                echo json_encode(["respuesta"=>$respuesta]);
                return;
            }
            $tarea = new Tarea($_POST);
            $tarea->proyectoId = $proyecto->id;
            $resultado = $tarea->guardar();
            if($resultado){
                $respuesta=[
                    "tipo"=> "exito",
                    "id" => $tarea->id,
                    "mensaje"=> "Tarea actualizada correctamente",
                    "proyectoId"=> $proyecto->id
                ];
                echo json_encode(["respuesta"=>$respuesta]);
            }
        }
    }
    public static function eliminar(){
        if($_SERVER["REQUEST_METHOD"]==="POST"){
            $proyecto = Proyecto::where("url",$_POST["proyectoId"]);
            session_start();
            if(!$proyecto||$proyecto->propietarioId!== $_SESSION["id"]){
                $respuesta=[
                    "tipo" => "error",
                    "mensaje"=>"Hubo un Error al borrar la tarea"
                ];
                echo json_encode($respuesta);
                return;
            }
            $tarea = new Tarea($_POST);
            $respuesta = $tarea->eliminar();
            $respuesta =[
                "respuesta"=> $respuesta,
                "tipo"=> "exito",
                "mensaje"=>"Tarea borrada correctamente"
            ];
            echo json_encode($respuesta);
        }
    }
    
}