<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bot_model extends CI_Model {
    public $table = 'bot_model';

    public function insertServerData($data)
    {   
        $this->db->insert('tb_server_status', $data);
        return $this->db->insert_id();
    }
}

/* End of file Bot_model.php */
