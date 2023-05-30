<?php
namespace Model;

use Model\ActiveRecord;

class Usuarios extends ActiveRecord{

    protected static $tabla ="usuarios";
    protected static $columnasDB=["id","nombre","email","password","token","confirmado"];

    public $id;
    public $nombre;
    public $email;
    public $password;
    public $password2;
    public $password_actual;
    public $password_nuevo;
    public $token;
    public $confirmado;

    public function __construct($args=[]){
        $this->id = $args["id"] ?? null;
        $this->nombre = $args["nombre"] ?? "";
        $this->email = $args["email"] ?? "";
        $this->password = $args["password"] ?? "";
        $this->password2 = $args["password2"] ?? "";
        $this->password_actual = $args["password"] ?? "";
        $this->password_nuevo = $args["password2"] ?? "";
        $this->token = $args["token"] ?? "";
        $this->confirmado = $args["confirmado"] ?? 0;
    }
    public function validarAuth():array{
        if(!$this->email){
            self::$alertas["error"][]="El Email del Usuario es Obligatorio";
        }
        if(!$this->password){
            self::$alertas["error"][]= "El Password no Puede ir vacio";
        }
        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas["error"][]="Email no válido";
        }
        return self::$alertas;
    }
    public function validarNuevaCuenta():array{
        if(!$this->nombre){
            self::$alertas["error"][]="El Nombre del Usuario es Obligatorio";
        }
        if(!$this->email){
            self::$alertas["error"][]="El Email del Usuario es Obligatorio";
        }
        if(!$this->password){
            self::$alertas["error"][]= "El Password no Puede ir vacio";
        }
        if(strlen($this->password)<6){
            self::$alertas["error"][]="El Password Debe contener al menos 6 caracteres";
        }
        if($this->password!==$this->password2){
            self::$alertas["error"][]="Los password som diferentes";
        }
        return self::$alertas;
    }
    public function validarEmail():array{
        if(!$this->email){
            self::$alertas["error"][]= "El email es Obligatorio";
        }
        if(!filter_var($this->email,FILTER_VALIDATE_EMAIL)){
            self::$alertas["error"][]="Email no válido";
        }
        return self::$alertas;
    }
    public function validarPassword():array{
        if(!$this->password){
            self::$alertas["error"][]= "El Password no Puede ir vacio";
        }
        if(strlen($this->password)<6){
            self::$alertas["error"][]="El Password Debe contener al menos 6 caracteres";
        }
        return self::$alertas;
    }
    public function password_nuevo():array{
        if(!$this->password_actual){
            self::$alertas["error"][]="El password Actual no puede ir vacio";
        }
        if(!$this->password_nuevo){
            self::$alertas["error"][]="El password Nuevo no puede ir vacio";
        }
        if(strlen($this->password_nuevo)<6){
            self::$alertas["error"][]="El password debe contener al menos 6 caracteres";
        }
        return self::$alertas;
    }
    public function comprobar_password():bool{
        return password_verify($this->password_actual,$this->password);
    }
    public function hashPassword():void{
        $this->password = password_hash($this->password,PASSWORD_BCRYPT);
    }
    public function tokenUnico():void{
        $this->token = uniqid();
    }

    public function validar_usuario():array{
        if(!$this->nombre){
            self::$alertas["error"][]="El nombre es obligatorio";
        }
        if(!$this->email){
            self::$alertas["error"][]="El email es obligatorio";
        }
        return self::$alertas;
    }
}