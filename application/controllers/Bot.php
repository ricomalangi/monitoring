<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Bot extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('bot_model');
    }

    public function index()
    {
        $token = "KEM#qFD1jfMk!n-21UA7";
        $target = "6281237573018";
        $server_down_message = "";
        $get_data_server_database = $this->db->select('server_name, vmid, status')->get('tb_server_status')->result_array();
       
        $get_data_server_api = $this->getNodes();
        
        function sortByFeildIDIndex($a, $b)
        {
            return strcmp($a['vmid'], $b['vmid']);
        }

        usort($get_data_server_api, 'sortByFeildIDIndex');
        foreach($get_data_server_database as $key => $value){
            if($get_data_server_api[$key]['status'] != $value['status']){
                if($get_data_server_api[$key]['status'] === 'stopped'){
                    $server_down_message .= "ALERT!!\n";
                    $server_down_message .= "Server Name: " . $get_data_server_api[$key]['server_name'] . "\n";
                    $server_down_message .= "VMID: " . $get_data_server_api[$key]['vmid'] . "\n";
                    $server_down_message .= "Status: " . $get_data_server_api[$key]['status'] . "\n";
                    $server_down_message .= "----------------------------------\n";
                }
                $this->db->where('vmid', $value['vmid'])->update('tb_server_status', ['status' => $get_data_server_api[$key]['status'] ]);
            }
        }
        if($server_down_message != ''){
            $curl = curl_init();
            
            curl_setopt_array($curl, array(
                CURLOPT_URL => 'https://api.fonnte.com/send',
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'POST',
                CURLOPT_POSTFIELDS => array(
                    'target' => $target,
                    'message' => "$server_down_message",
                    'countryCode' => '62', //optional
                ),
                CURLOPT_HTTPHEADER => array(
                    "Authorization: $token" //change TOKEN to your actual token
                ),
            ));
    
            $response = curl_exec($curl);
            $err = curl_error($curl);
    
            curl_close($curl);
    
            if ($err) {
                echo "cURL Error #:" . $err;
            }
            echo $response;
        }
    }

    private function curlOptions()
    {
        $curlOptions = [
            CURLOPT_HTTPHEADER => [
                "CSRFPreventionToken:" . $this->session->userdata('token'),
                "Cookie: PVEAuthCookie=" . $this->session->userdata('ticket')
            ]
        ];
        return $curlOptions;
    }

    public function getNodes()
    {
        $url = $this->session->userdata('ProxmoxApiURL') . 'nodes/' . $this->node_name . '/lxc';
        $r = $this->curl_call($url, $this->curlOptions());
        if (empty($r['error']) && !empty($r['response'])) {
            $data = @json_decode($r['response'], true);
            $result = [];
            if (!is_null($data['data'])) {
                foreach ($data['data'] as $item) {
                    array_push($result, [
                        'server_name' => $item['name'],
                        'vmid' => $item['vmid'],
                        'status' => $item['status'],
                    ]);
                }
                return $result;
            } else {
                return false;
            }
        }
    }

    public function generateMessage()
    {
        $message = '';
        foreach($this->getNodes() as $item){
            $message .= "Server Name: " . $item['server_name'] . "\n";
            $message .= "VMID: " . $item['vmid'] . "\n";
            $message .= "Status: " . $item['status'] . "\n";
            $message .= "----------------------------------\n";
        }
        return $message;
    }

 
    public function insertDataServer()
    {
        $data = [
            'data' => $this->getNodes()
        ];

        function sortByFeildID($a, $b)
        {
            return strcmp($a['vmid'], $b['vmid']);
        }

        usort($data['data'], 'sortByFeildID');
        foreach ($data['data'] as $item) {
            $this->bot_model->insertServerData($item);
        }
    }

    public function shedule_one_minute()
    {
        $this->index();
        echo("<script type='text/javascript'>setInterval('window.location.reload()', 10000);</script>");
    }
}

/* End of file Bot.php */
