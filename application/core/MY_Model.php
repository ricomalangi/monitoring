<?php

defined('BASEPATH') or exit('No direct script access allowed');

class MY_Model extends CI_Model
{
    protected $database = 'monitoring';
    protected $collection = '';
    protected $conn;

    public function __construct()
    {
        parent::__construct();
        $this->load->library('mongodb');
        $this->conn = $this->mongodb->getConn();
    }
    public function get_data($id = null)
    {
        try {
            if ($id == null) {
                $filter = [];
                $query = new MongoDB\Driver\Query($filter);
                $result = $this->conn->executeQuery($this->database . '.' . $this->collection, $query);
            } else {
                $query = new MongoDB\Driver\Query(['vmid' => $id]);
                $result = $this->conn->executeQuery($this->database . '.' . $this->collection, $query);
            }
            //echo("<pre>"); print_r($query); echo("</pre>"); die();

            return $result;
        } catch (MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while fetching users: ' . $ex->getMessage(), 500);
        }
    }

    public function create_data($data)
    {
        try {
            foreach ($data['data'] as $dt) {
                $query = new MongoDB\Driver\BulkWrite();
                $dt['timestamps'] = time() * 1000;
                $dt['date'] = date('Y-m-d H:i:s');
                $query->insert($dt);

                $this->conn->executeBulkWrite($this->database . '.' . $this->collection, $query);
            }
        } catch (MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while saving data: ' . $ex->getMessage(), 500);
        }
    }

    public function update_data($_id, $name, $email)
    {
        try {
            $query = new MongoDB\Driver\BulkWrite();
            $query->update(['_id' => new MongoDB\BSON\ObjectId($_id)], ['$set' => array('name' => $name, 'email' => $email)]);

            $result = $this->conn->executeBulkWrite($this->database . '.' . $this->collection, $query);

            if ($result == 1) {
                return TRUE;
            }

            return FALSE;
        } catch (MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while updating users: ' . $ex->getMessage(), 500);
        }
    }

    public function delete_data($_id)
    {
        try {
            $query = new MongoDB\Driver\BulkWrite();
            $query->delete(['_id' => new MongoDB\BSON\ObjectId($_id)]);

            $result = $this->conn->executeBulkWrite($this->database . '.' . $this->collection, $query);

            if ($result == 1) {
                return TRUE;
            }

            return FALSE;
        } catch (MongoDB\Driver\Exception\RuntimeException $ex) {
            show_error('Error while deleting users: ' . $ex->getMessage(), 500);
        }
    }
}

/* End of file ModelName.php */
