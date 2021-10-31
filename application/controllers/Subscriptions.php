<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once(APPPATH . 'libraries/Emailsend.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

class Subscriptions extends REST_Controller
{

    public function __construct()
    {

        header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent::__construct();

        $this->load->database();

        /** Se colocara la llamada o carga del rest Token */
    }

    public function create_subscription_post($token = "0", $id_usuario = "0")
    {

        $data = $this->post();

        if ($token == "0" || $id_usuario == "0") {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "Token invalido y/o usuario invalido."
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        if (!isset($data["email_subscription"])) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "Faltan datos en el servicio de creacion de subscripcion!!!"
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        /** Busca si existe el subscriptor */
        $email_subscription = $data["email_subscription"];
        $condiciones = array('subscription_email_user' => $email_subscription);
        $this->db->where($condiciones);
        $query = $this->db->get('pdg_subscriptions');

        $existe = $query->row();

        if ($existe) {
            $respuesta = array(
                'error' => "true",
                'mensaje' => "Cuenta correo ya esta registrada en nuestros sistemas!!!"
            );

            $this->response($respuesta);
            
        } else {

            /** Voy a guardar la data */

            $date_created = date("Y:m:d H:i:s");

            $insertarSubscription = array(
                'subscription_email_user'  => $email_subscription,
                'subscription_create_date' => $date_created
            );

            $this->db->insert('pdg_subscriptions', $insertarSubscription);
            $record_insert = $this->db->insert_id();

            $respuesta = array(
                'error' => "false",
                'mensaje' => "Solicitud de Subscripcion guardada correctamente!!!"
               // 'id' => $record_insert
            );

            $this->response($respuesta);
           
        }

     
    }
}
