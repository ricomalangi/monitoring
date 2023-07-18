<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function index()
    {
        $this->load->view('auth/index');
    }

    public function authenticate()
    {
        $api_url = (substr($this->input->post('api_url'), -1) !== '/') ? $this->input->post('api_url') . '/' : $this->input->post('api_url');
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $curlOptions = [
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => 'username=' . $username . '&password=' . $password,
            CURLOPT_HTTPHEADER => ['Content-Type: application/x-www-form-urlencoded']
        ];

        $r = $this->curl_call($api_url . 'access/ticket', $curlOptions);
        if (empty($r['error']) && !empty($r['response'])) {
            $data = @json_decode($r['response'], true);
            if (!is_null($data['data'])) {
                $this->session->set_userdata('ProxmoxApiURL', $api_url);
                $this->session->set_userdata('ticket', $data['data']['ticket']);
                $this->session->set_userdata('token', $data['data']['CSRFPreventionToken']);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('is_login', TRUE);
                redirect(base_url('dashboard'));
            }
            redirect(base_url('auth'));
        }
    }

    public function logout()
    {
        $sess_data = [
            'ProxmoxApiURL', 'ticket', 'token', 'username', 'is_login'
        ];
        $this->session->unset_userdata($sess_data);
        $this->session->sess_destroy();
        redirect(base_url());
    }
}
