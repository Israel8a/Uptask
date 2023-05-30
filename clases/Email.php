<?php
namespace Clases;

use PHPMailer\PHPMailer\PHPMailer;

class Email{
    protected $nombre;
    protected $email;
    protected $token;

    public function __construct($nombre,$email,$token){
        $this->nombre = $nombre;
        $this->email = $email;
        $this->token = $token;
    }
    public function enviarConfirmacion(){
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = 'sandbox.smtp.mailtrap.io';
        $email->SMTPAuth = true;
        $email->Port = 2525;
        $email->Username = '9bbf6274e888f5';
        $email->Password = 'e2021028c58c95';

        $email->setFrom("cuentas@uptask.com");
        $email->addAddress("cuentas@uptask.com","UpTask.com");
        $email->Subject="confirma tu cuenta";
        $email->isHTML(true);
        $email->CharSet ="UTF-8";

        $contenido ="<html>";
        $contenido.="<p><strong>Hola ".$this->nombre."</strong>  Has creado tu cuenta en UpTask, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido.="<p>Presione aqui: <a href='https://uptaskk.herokuapp.com/confirmar?token=".$this->token."'>Confirmar Cuenta</p></a>";
        $contenido.="<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido.="</html>";
        $email->Body = $contenido;
        // aqui enviamos el email
        $email->send();
    }
    public function enviarInstrucciones(){
        $email = new PHPMailer();
        $email->isSMTP();
        $email->Host = 'sandbox.smtp.mailtrap.io';
        $email->SMTPAuth = true;
        $email->Port = 2525;
        $email->Username = '9bbf6274e888f5';
        $email->Password = 'e2021028c58c95';

        $email->setFrom("cuentas@uptask.com");
        $email->addAddress("cuentas@uptask.com","UpTask.com");
        $email->Subject="Restablece tu password";
        $email->isHTML(true);
        $email->CharSet ="UTF-8";

        $contenido ="<html>";
        $contenido.="<p><strong>Hola ".$this->nombre."</strong> Has solicitado reestablecer tu password, sigue el enlace para hacerlo</p>";
        $contenido.="<p>Presione aqui: <a href='https://uptaskk.herokuapp.com/reestablecer?token=".$this->token."'>Reestablecer Password</p></a>";
        $contenido.="<p>Si tu no solicitaste esta cuenta, puedes ignorar el mensaje</p>";
        $contenido.="</html>";
        //agregar el contenido a email
        $email->Body = $contenido;
        //enviar email
        $email->send();

    }
}