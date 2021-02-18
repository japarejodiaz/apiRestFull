<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

class Email extends REST_Controller
{

    function  __construct()
    {
        parent::__construct();
    }

    function sendmail_post($token = "0", $id_usuario = "0")
    {
        // Load el Post de los Requests 
        $data = $this->post();

        if ($token == "0" || $id_usuario == "0") {

            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "Token invalido y/o usuario invalido."
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        if (
            !isset($data["email"])   ||
            !isset($data["subject"]) ||
            !isset($data["message"]) ||
            !isset($data["name"])
        ) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "Faltan datos en el post de entrada"
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }


        /** manda el correo a una cuenta pote de la agencia */

        $dataSendMails = $arrayName = array(
            'emailUser' => $data["email"],
            'nameUser' => $data["name"],
            'subject' => $data["subject"],
            'messageBody' =>  $data["message"]
        );
        // Load PHPMailer library
        $this->load->library('phpmailer_lib');

        // PHPMailer object
        $mail = $this->phpmailer_lib->load();

        // SMTP configuration
        $mail->isSMTP();
        $mail->isHTML(true);
        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'japarejo.diaz@gmail.com';
        $mail->Password = 'Matias_010213';
        $mail->SMTPSecure = 'TLS';
        $mail->Port     = 587;
        $mail->CharSet = 'UTF-8';   /*Codificación del mensaje*/

        $mail->setFrom('agenciapdgroup@gmail.com', 'Agencia PDGroup Web Systems');
        $mail->addReplyTo('agenciapdgroup@gmail.com', 'Agencia PDGroup Web Systems');

        // Add a recipient
        $mail->addAddress('agenciapdgroup@gmail.com');

        // Add cc or bcc 
        $mail->addCC('japarejo.diaz@desatisolution.com.ve');
        $mail->addCC('aparejodiaz@gmail.com');
        $mail->addCC('lrfernandez.img@gmail.com');
        // $mail->addBCC('bcc@example.com');

        // Email subject
        $mail->Subject = 'Solicitud de Informacion de Productos - Ventas';

        // Set email format to HTML

        // Email body content
        $mailContent = "<h3>Solicitud de Informacion de Ventas</h3>"
            . "</br>"
            . "<p>Usuario: " . $data["name"] . ", "  . "Correo Electronico: " . $data["email"] . "</p>"
            . "</br>"
            . "<p>Asunto del Mensaje: " .  $data["subject"] . "</p>"
            . "</br>"
            . "<p>Mensaje del Cliente: " . $data["message"] . "</p>"
            . "</br>"
            . "</br>"
            . "</br>"
            . "</br>"
            . "</br>"
            . "<h4>Equipo de Reclutamiento y Productos de PDGroup</h4>";

        $mail->Body = $mailContent;

        // Send email
        if (!$mail->send()) {
            $respuesta = array(
                'error' => true,
                'mensaje' => 'Error de envio de notificacion de Correo: ' . $mail->ErrorInfo
            );

            $this->response($respuesta);
        } else {
           
            $respuesta = array(
                'error' => FALSE,
                'mensaje' => 'Solicitud guardada correctamente!!!'
            );

            $this->response($respuesta);
        }
    }
}
