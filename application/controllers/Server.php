<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Server extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('is_login') == FALSE) {
            $this->session->set_flashdata('alert', 'Anda Belum login, silahkan login terlebih dahulu !');
            redirect(base_url('auth'));
        }
        $this->load->model('Server_resource_model', 'resource');
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

    public function index()
    {
        $data = [
            'data' => $this->getNodes()
        ];

        function sortByFeildID($a, $b)
        {
            return strcmp($a['vmid'], $b['vmid']);
        }

        usort($data['data'], 'sortByFeildID');

        $this->load->view('templates/v_header');
        $this->load->view('server/index', $data);
        $this->load->view('templates/v_footer');
    }

    public function detail($vmid)
    {
        $url = $this->session->userdata('ProxmoxApiURL') . "nodes/$this->node_name/lxc/$vmid/config";
        $r = $this->curl_call($url, $this->curlOptions());
        if (empty($r['error']) && !empty($r['response'])) {
            $result = @json_decode($r['response'], true);
        }
        $data = [
            'data' => $result['data'],
            'grafik' => $this->resource->get_data($vmid),
            'vmid' => $vmid
        ];
        $this->load->view('templates/v_header');
        $this->load->view('server/detail', $data);
        $this->load->view('templates/v_footer');
    }

    public function shutdown($vmid)
    {
        $url = $this->session->userdata('ProxmoxApiURL') . "nodes/$this->node_name/lxc/$vmid/status/shutdown";
        $r = $this->curl_call($url, $this->curlOptions(), "POST");
        if (empty($r['error']) && !empty($r['response'])) {
            $data = @json_decode($r['response'], true);
            if ($data['data'] != "") {
                $this->session->set_flashdata('success', "Server dengan id $vmid berhasil dimatikan");
            } else {
                $this->session->set_flashdata('error', 'Ooops terdapat kesalahan');
            }
            redirect(base_url('server'));
        }
    }

    public function poweron($vmid)
    {
        $url = $this->session->userdata('ProxmoxApiURL') . "nodes/$this->node_name/lxc/$vmid/status/start";
        $r = $this->curl_call($url, $this->curlOptions(), "POST");
        if (empty($r['error']) && !empty($r['response'])) {
            $data = @json_decode($r['response'], true);
            if ($data['data'] != "") {
                $this->session->set_flashdata('success', "Server dengan id $vmid berhasil dinyalakan");
            } else {
                $this->session->set_flashdata('error', 'Ooops terdapat kesalahan');
            }
            redirect(base_url('server'));
        }
    }

    public function graph_memory($vmid)
    {
        $data = $this->resource->get_data($vmid);
        $grafik = [];

        foreach ($data as $item) {
            array_push($grafik, ["x" => $item->timestamps, "y" => $item->mem]);
        }
        header('Content-Type: application/json');
        echo json_encode($grafik);
    }

    public function graph_cpu($vmid)
    {
        $data = $this->resource->get_data($vmid);
        $grafik = [];
        foreach ($data as $item) {
            array_push($grafik, ["x" => $item->timestamps, "y" => round($item->cpu * 100 / $item->cpus, 3)]);
        }
        header('Content-Type: application/json');
        echo json_encode($grafik);
    }

    private function getNodes()
    {
        $url = $this->session->userdata('ProxmoxApiURL') . 'nodes/' . $this->node_name . '/lxc';
        $r = $this->curl_call($url, $this->curlOptions());
        if (empty($r['error']) && !empty($r['response'])) {
            $data = @json_decode($r['response'], true);
            $result = [];
            if (!is_null($data['data'])) {
                foreach ($data['data'] as $item) {
                    array_push($result, [
                        'maxmem' => round($item['maxmem'] / 1024 / 1024, 4),
                        'name' => $item['name'],
                        'vmid' => $item['vmid'],
                        'status' => $item['status'],
                        'uptime' => $this->secondsToTime($item['uptime'])
                    ]);
                }
                return $result;
            } else {
                return false;
            }
        }
    }

    public function insertData()
    {
        $url = $this->session->userdata('ProxmoxApiURL') . 'nodes/' . $this->node_name . '/lxc';
        $r = $this->curl_call($url, $this->curlOptions());
        if (empty($r['error']) && !empty($r['response'])) {
            $data = @json_decode($r['response'], true);
            if (!is_null($data['data'])) {
                return $this->resource->create_data($data);
            } else {
                return false;
            }
        }
    }

    public function getData($id = null)
    {
        if ($id == null) {
            $data = $this->resource->get_data();
            foreach ($data as $item) {
                echo ("<pre>");
                print_r($item);
                echo ("</pre>");
            }
        } else {
            $data = $this->resource->get_data($id);
            foreach ($data as $item) {
                echo ("<pre>");
                print_r($item);
                echo ("</pre>");
            }
        }
    }

    public function shedule_one_minute()
    {
        $this->insertData();
        echo("<script type='text/javascript'>setInterval('window.location.reload()', 10000);</script>");
    }
}
