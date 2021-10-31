<?php
require APPPATH . '/libraries/CreatorJwt.php';


require_once(APPPATH . '/libraries/REST_Controller.php');

use Restserver\libraries\REST_Controller;

class JwtToken extends REST_Controller
{
    public function __construct()
    {  

        header("Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");
        header("Access-Control-Allow-Origin: *");
        
        parent::__construct();
        $this->objOfJwt = new CreatorJwt();
        header('Content-Type: application/json');
        $this->load->database();
    }

    /*************Ganerate token this function use**************/

    public function login_post()
    {

        $data = $this->post();

       /*  echo $data['correo'];
        echo $data['contrasena'];  */

        if (!isset($data['correo']) or !isset($data['contrasena'])) {

            $respuesta = array(
                'error' => TRUE,
                'mensaje' => 'La información enviada no es válida'
            );
            $this->response($respuesta, REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        $tokenData['uniqueId'] = '12';   // 11
        // $tokenData['role'] = 'alamgir';
        $tokenData['role'] = $data['correo'];
        $tokenData['timeStamp'] = Date('Y-m-d h:i:s');
        $jwtToken = $this->objOfJwt->GenerateToken($tokenData);
        //echo json_encode(array('Token'=>$jwtToken));


        $respuesta = array(
            'error' => FALSE,
            'mensaje' => "Token es correcto.",
            'correo' => $data['correo'],
            'token' => $jwtToken,
            'expire_in' => 3600    
        );
        $this->response($respuesta, REST_Controller::HTTP_OK);
       

    }
     
   /*************Use for token then fetch the data**************/
         
    public function get_token_get()
    {
    $received_Token = $this->input->request_headers('Authorization');
        try
        {
            $jwtData = $this->objOfJwt->DecodeToken($received_Token['Token']);

            $respuesta = array(
                'error' => FALSE,
                'mensaje' => "Token es correcto.",
                'token' => $jwtData
            );
            $this->response($respuesta, REST_Controller::HTTP_OK);



          //  echo json_encode($jwtData);
        }
        catch (Exception $e)
            {

            $respuesta = array(
                'error' => TRUE,
                'mensaje' => $e->getMessage(),
                
            );
            $this->response($respuesta, REST_Controller::HTTP_UNAUTHORIZED);    
            
        }
    }
}
        