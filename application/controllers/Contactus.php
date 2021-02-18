<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once(APPPATH . 'libraries/Emailsend.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

class Contactus extends REST_Controller
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


    public function buscar_contact_us_email_get($token = "0", $usuario = "0", $email, $motive)
    {

        if ($token == "0" || $usuario == "0") {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "Token invalido y/o usuario invalido."
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        /** Especifica la pagina y la seccion de la pagina a regresar */

        $condiciones = array('email_request_email' => $email, 'email_request_motive' => $motive);
        $this->db->where($condiciones);
        $query = $this->db->get('pdg_email_request');

        $existe = $query->row();

        if ($existe) {
            $respuesta = array(
                'error' => "true",
                'mensaje' => "Ya correo esta registrado"
            );
            $this->response($respuesta);
            return;
        } else {
            $respuesta = array(
                'error' => "false",
                'mensaje' => "Puede registrar una nueva solicitud"
            );
            $this->response($respuesta);
            return;
        }
    }



    public function create_contact_us_post($token = "0", $id_usuario = "0")
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

        /** Voy a guardar la data */

        $date_created = date("Y:m:d H:i:s");

        $insertarSolInfo = array(
            'email_request_motive'  => $data["subject"],
            'email_request_nombre'  => $data["name"],
            'email_request_message' => $data["message"],
            'email_request_email'   => $data["email"],
            'email_date_created'    => $date_created
        );

        $this->db->insert('pdg_email_request', $insertarSolInfo);
        $record_insert = $this->db->insert_id();

        $respuesta = array(
            'error' => FALSE,
            'mensaje' => 'Solicitud guardada correctamente',
            'id' => $record_insert
        );

        $this->response($respuesta);
    }
}
