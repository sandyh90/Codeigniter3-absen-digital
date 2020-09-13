<?php

function is_logged_in()
{
    $ci = get_instance();
    $ci->load->model('M_Auth');
    $username = $ci->session->userdata('username');
    if (!$ci->session->userdata('logged_in')) {
        if (get_cookie('absensi_rememberme')) {
            $ci->M_Auth->check_rememberme();
        } else {
            redirect('login');
        }
    } elseif (empty($ci->db->get_where('user', ['username' => $username])->row_array())) {
        $ci->session->sess_destroy();
        redirect('login');
    }
}

function rememberme_check()
{
    $ci = get_instance();
    $ci->load->model('M_Auth');
    if (get_cookie('absensi_rememberme')) {
        $ci->M_Auth->check_rememberme();
    }
}


function is_admin()
{
    $ci = get_instance();
    $role_id = $ci->session->userdata('role_id');

    if ($role_id != 1) {
        redirect('block');
    }
}

function is_moderator()
{
    $ci = get_instance();
    $role_id = $ci->session->userdata('role_id');

    if ($role_id != 1 && $role_id != 2) {
        redirect('block');
    }
}
