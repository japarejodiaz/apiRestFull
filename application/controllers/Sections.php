<?php
defined('BASEPATH') or exit('No direct script access allowed');

require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

class Sections extends REST_Controller
{

    public function __construct()
    {

        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");

        parent::__construct();

        $this->load->database();

        /** Se colocara la llamada o carga del rest Token */
    }


    public function obtener_page_section_get($token = "0", $usuario = "0", $pagina, $section)
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

        $condiciones = array('page_id' => $pagina, 'section_type_id' => $section);
        $this->db->where($condiciones);
        $query = $this->db->get('pdg_section_site');

        $existe = $query->row();

        if (!$existe) {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "No existe informacion para esta pagina y seccion"
            );
            $this->response($respuesta);
            return;
        }

        /** Revisa en base de datos la seccion a devolver - */

        $query = $this->db->query("SELECT * FROM `pdg_section_site` where page_id = " . $pagina . " and section_type_id = " . $section);

        $page_section = array();

        foreach ($query->result() as $row) {

            $query_detalle = $this->db->query("SELECT psi.*, pss.section_id from pdg_section_icons psi inner join pdg_section_site pss on psi.section_id = pss.section_id where psi.section_id = " . $row->section_id);

            $page_section_icon = array(
                'id' => $row->section_id,
                'page_id' => $row->page_id,
                'section_type_id' => $row->section_type_id,
                'section_title_h2' => $row->section_title_h2,
                'section_title_h4' => $row->section_title_h4,
                'section_title_h3' => $row->section_title_h3,
                'section_parrafo_h3' => $row->section_parrafo_h3,
                'section_parrafo_h4' => $row->section_parrafo_h4,
                'section_parrafo_h2' => $row->section_parrafo_h2,
                'section_title_h5' => $row->section_title_h5,
                'section_parrafo_h5' => $row->section_parrafo_h5,
                'section_url_img' => $row->section_url_img,
                'url_redirect' => $row->url_redirect,
                'detalle' => $query_detalle->result()
            );

            array_push($page_section, $page_section_icon);
        }

        $respuesta = array(
            'error' => FALSE,
            'page_section' => $page_section
        );

        $this->response($respuesta);
    }
}
