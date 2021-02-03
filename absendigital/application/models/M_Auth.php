<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Auth extends CI_Model
{
    public function check_rememberme()
    {
        $get_cookie_rmb = get_cookie('absensi_rememberme');
        $get_hash = $this->db->get_where('db_rememberme', ['cookie_hash' => $get_cookie_rmb])->row_array();
        $user = $this->db->get_where('user', ['username' => $get_hash['username']])->row_array();
        //check hash cookie
        if (hash_equals($get_cookie_rmb, $get_hash['cookie_hash'])) {
            if (time() - $get_hash['expired'] > '31570560') {
                $create_ses = [
                    'username' => $user['username'],
                    'role_id' => $user['role_id'],
                    'logged_in' => true
                ];
                $this->db->where('user.id_pegawai', $user['id_pegawai']);
                $this->db->update('user', ['is_online' => 1]);
                $this->session->set_userdata($create_ses);
                //redirect(base_url()); //Mengarahkan otomatis user ke halaman login
            } else {
                $this->db->delete('db_rememberme', ['cookie_hash' => $get_hash['cookie_hash']]);
            }
        } else {
            redirect('login'); //Mengarahkan otomatis user ke halaman login
        }
    }

    public function do_login()
    {
        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $user = $this->db->get_where('user', ['username' => $username])->row_array();
        if ($user) {
            //jika user aktif
            if ($user['is_active'] == 1) {
                //check password
                if (password_verify($password, $user['password'])) {
                    $membuat_session = [
                        'username' => $user['username'],
                        'role_id' => $user['role_id'],
                        'logged_in' => true
                    ];
                    $this->db->where('user.id_pegawai', $user['id_pegawai']);
                    $this->session->set_userdata($membuat_session); //Memasukan / menyimpan data ke session
                    if (!empty($this->input->post('rememberme'))) {
                        $hash_rememberme = base64_encode(random_bytes(32));
                        $exp_rememberme = '31570560';
                        set_cookie('absensi_rememberme', hash('SHA256', $hash_rememberme), $exp_rememberme);
                        $this->_db_session($hash_rememberme, $user, $exp_rememberme);
                    }
                    redirect(base_url());
                } else {
                    $this->session->set_flashdata('authmsg', '<div class="alert alert-danger" role="alert">Password Atau Username Salah!</div>');
                    redirect('login');
                }
            } else {
                $this->session->set_flashdata('authmsg', '<div class="alert alert-danger" role="alert">Akun Ini Belum Aktif, Silakan Hubungi Pihak Administrator!</div>');
                redirect('login');
            }
        } else {
            $this->session->set_flashdata('authmsg', '<div class="alert alert-danger" role="alert">Akun Belum Terdaftar!</div>');
            redirect('login');
        }
    }

    private function _db_session($hash_rememberme, $user, $exp_rememberme)
    {
        if ($this->agent->is_browser()) {
            $agent = $this->agent->browser() . ' ' . $this->agent->version();
        } elseif ($this->agent->is_mobile()) {
            $agent = $this->agent->mobile();
        } else {
            $agent = 'Unknown';
        }

        $save_sessdb = [
            'username' => $user['username'],
            'user_agent' => $agent,
            'agent_string' => $this->agent->agent_string(),
            'platform' => $this->agent->platform(),
            'user_ip' => $this->input->ip_address(),
            'cookie_hash' => hash('SHA256', $hash_rememberme),
            'expired' => $exp_rememberme,
            'date_created' => time()
        ];
        $this->db->insert('db_rememberme', $save_sessdb);
    }

    public function do_logout()
    {
        $update_db = [
            'last_login' => time()
        ];
        $this->db->where('username', $this->session->userdata('username'));
        $this->db->update('user', $update_db);
        if ($this->session->userdata('username')) {
            $this->session->sess_destroy();
            if (!empty(get_cookie('absensi_rememberme'))) {
                $get_cookie_rmb = get_cookie('absensi_rememberme');
                $this->db->delete('db_rememberme', ['cookie_hash' => $get_cookie_rmb]);
                delete_cookie('absensi_rememberme');
            }
        } else {
            $this->session->set_flashdata('authmsg', '<div class="alert alert-danger" role="alert">You are not logged in!</div>'); //Mengirimkan informasi sukses ke halaman login
            redirect('login'); //Mengarahkan otomatis user ke halaman login
        }
    }
}
