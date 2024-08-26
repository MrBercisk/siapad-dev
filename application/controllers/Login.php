<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Login extends CI_Controller
{
    public function index()
    {
        $this->load->view('login.php');
    }
    public function getAuth()
    {
        $username = $_POST['username'];
        $pass = $_POST['userpassword'];

        $password = md5($pass);

        $this->db->where('login', $username);
        $this->db->where('passwd', $password);
        $query =  $this->db->get('sys_user');
        if ($query->num_rows() > 0) {
            // Login berhasil
            $user = $query->row();
            $this->session->set_userdata('role_id', $user->role);
            $this->session->set_flashdata('message', 'Login berhasil');
            redirect('/');
        } else {
            // Login gagal
            $this->session->set_flashdata('message', 'Invalid username dan password');
            redirect('login');
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('login');
    }
}
