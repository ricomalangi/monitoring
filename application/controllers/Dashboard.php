<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {
  
  public function __construct()
  {
    parent::__construct();
    if($this->session->userdata('is_login') == FALSE){
      $this->session->set_flashdata('alert','Anda Belum login, silahkan login terlebih dahulu !');
      redirect(base_url('auth'));
    }
  }
  
  private function curlOptions(){
    $curlOptions = [
      CURLOPT_HTTPHEADER => [
        "CSRFPreventionToken:". $this->session->userdata('token'),
        "Cookie: PVEAuthCookie=". $this->session->userdata('ticket')
      ]
    ];
    return $curlOptions;
  }

	public function index()
	{
    $data = [
      'data' => $this->server_status()
    ];
    //echo("<pre>");print_r($data); echo("</pre>"); die();
    $this->load->view('templates/v_header');
		$this->load->view('home', $data);
    $this->load->view('templates/v_footer');
	}

  public function server_status(){
    $url = $this->session->userdata('ProxmoxApiURL') . 'nodes/' . $this->node_name . '/lxc';
    $r = $this->curl_call($url, $this->curlOptions());
    if(empty($r['error']) && !empty($r['response'])){
      $data = @json_decode($r['response'], true);
      $status['total_stopped'] = 0;
      $status['total_running'] = 0;
      $status['total_node'] = count($data['data']);
      $status['nodes_up'] = [];
      $status['nodes_down'] = [];
      foreach($data['data'] as $item){
        if($item['status'] == 'stopped'){
          $status['total_stopped'] += 1;
          array_push($status['nodes_down'],[
            'name' => $item['name'],
            'vmid' => $item['vmid']
          ]);
        } 
        if($item['status'] == 'running'){
          $status['total_running'] += 1;
          array_push($status['nodes_up'],[
            'name' => $item['name'],
            'vmid' => $item['vmid']
          ]);
        }
      }
      if(!is_null($data['data'])){
        return $status;
      } else {
        return false;
      }
    }
  }
}
