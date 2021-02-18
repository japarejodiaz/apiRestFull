<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Cliente_model extends CI_Model {

    public $id;
    public $nombre;
    public $correo;

    public function get_cliente ( $id ) {

        $this->id = $id;
        $this->nombre = 'Jesus';
        $this->correo = 'japarejo.diaz@gmail.com';

        return $this;

    }

    public function insert() {

        return 'Insertado';
    }

    public function update()
    {

        return 'Actualizado';
    }

    public function delete()
    {

        return 'Borrado';
    }


}