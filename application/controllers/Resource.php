<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * Author: https://roytuts.com
 */

class Resource extends CI_Controller
{

  public function __construct()
  {
    parent::__construct();
    $this->load->model('model_resource');
  }

  function index()
  {
    $data['users'] = $this->model_resource->get_user_list();
    $this->load->view('users', $data);
  }

  public function create()
  {
    if ($this->input->post('submit')) {
      $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
      $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|required');

      if ($this->form_validation->run() !== FALSE) {
        $result = $this->model_resource->create_user($this->input->post('name'), $this->input->post('email'));
        if ($result === TRUE) {
          redirect('/resource');
        } else {
          $data['error'] = 'Error occurred during saving data';
          $this->load->view('user_create', $data);
        }
      } else {
        $data['error'] = 'Error occurred during saving data: all fields are required';
        $this->load->view('user_create', $data);
      }
    } else {
      $this->load->view('user_create');
    }
  }

  function update($_id)
  {
    if ($this->input->post('submit')) {
      $this->form_validation->set_rules('name', 'Full Name', 'trim|required');
      $this->form_validation->set_rules('email', 'Email Address', 'trim|valid_email|required');

      if ($this->form_validation->run() !== FALSE) {
        $result = $this->model_resource->update_user($_id, $this->input->post('name'), $this->input->post('email'));
        if ($result === TRUE) {
          redirect('/');
        } else {
          $data['error'] = 'Error occurred during updating data';
          $this->load->view('user_update', $data);
        }
      } else {
        $data['error'] = 'error occurred during saving data: all fields are mandatory';
        $this->load->view('user_update', $data);
      }
    } else {
      $data['user'] = $this->model_resource->get_user($_id);
      $this->load->view('user_update', $data);
    }
  }

  function delete($_id)
  {
    if ($_id) {
      $this->model_resource->delete_user($_id);
    }
    redirect('/');
  }
}
