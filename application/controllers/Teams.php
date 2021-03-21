<?php
defined('BASEPATH') or exit('No direct script access allowed');

//require_once(APPPATH . 'libraries/Emailsend.php');
require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

class Teams extends REST_Controller
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


    public function buscar_teams_all_get($token = "0", $usuario = "0")
    {

        if ($token == "0" || $usuario == "0") {
            $respuesta = array(
                'error' => TRUE,
                'mensaje' => "Token invalido y/o usuario invalido."
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        /** Busqueda de todos los resgistros de la tabla */

        $query = $this->db->query('SELECT teams_id, pt.teams_position_id, teams_apellidos, teams_nombres, 
                                    teams_email, teams_telephone_1, teams_telephone_2, pt.country_id as id_country, 
                                    pc.country_name as name_country, ppt.position_type_name as name_position, url_img_member_teams, teams_parrafo_card,
                                    facebook_have, url_facebook_social_net, twitter_have, url_twitter_social_net, whatsapp_have, 
                                    url_whatsapp_social_net, address_uri_qr_whatsapp, linkedin_have, url_linkedin_social_net
                                    FROM pdg_teams pt
                                    inner join pdg_position_type ppt on ppt.position_type_id = pt.teams_position_id 
                                    inner join pdg_countries pc on pt.country_id 
                                    where ppt.position_type_id = pt.teams_position_id and 
                                         pc.country_id = pt.country_id ');

        /** Especifica la pagina y la seccion de la pagina a regresar */


        $existe = $query->row();

        $page_section_teams = array();

        if (!$existe) {
            $respuesta = array(
                'error' => "true",
                'mensaje' => "No hay colaboradores registrados en el sistema"
            );
            $this->response($respuesta);
            return;
        } else {

            /** Si hay registros de colaboradores se devuelven los registros con sus relaciones */

            foreach ($query->result() as $row) {

                /* $query_teams_detalle =
                    $this->db->query("SELECT ptsn.*, psnt.social_network_type_id as id_social_net, psnt.social_network_name as name_social_net from pdg_teams_social_networks ptsn 
                                    inner join pdg_social_network_type psnt on ptsn.teams_social_networktype_id = psnt.social_network_type_id
                                    where ptsn.teams_id = " . $row->teams_id);
 */

                $page_section_social_net = array(
                    'teams_id' => $row->teams_id,
                    'teams_position_id' => $row->teams_position_id,
                    'name_position' => $row->name_position,
                    'teams_apellidos' => $row->teams_apellidos,
                    'teams_nombres' => $row->teams_nombres,
                    'teams_telephone_1' => $row->teams_telephone_1,
                    'teams_telephone_2' => $row->teams_telephone_2,
                    'id_country' => $row->id_country,
                    'name_country' => $row->name_country,
                    'url_img_member_teams' => $row->url_img_member_teams,
                    'teams_parrafo_card' => $row->teams_parrafo_card,
                    'facebook_have' => $row->facebook_have,
                    'url_facebook_social_net' => $row->url_facebook_social_net,
                    'twitter_have' => $row->twitter_have,
                    'url_twitter_social_net' => $row->url_twitter_social_net,
                    'whatsapp_have' => $row->whatsapp_have,
                    'url_whatsapp_social_net' => $row->url_whatsapp_social_net,
                    'address_uri_qr_whatsapp' => $row->address_uri_qr_whatsapp,
                    'linkedin_have' => $row->linkedin_have,
                    'url_linkedin_social_net' => $row->url_linkedin_social_net
                );

                array_push($page_section_teams, $page_section_social_net);
            }

            $respuesta = array(
                'error' => FALSE,
                'numero_registros' => $query->num_rows(),
                'page_section' => $page_section_teams
            );

            $this->response($respuesta);
            return;
        }
    }
}
