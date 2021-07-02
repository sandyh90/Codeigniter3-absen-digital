<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_Front extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $bulan = array(1 => "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
        $hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
        $this->get_today_date = $hari[(int)date("w")] . ', ' . date("j ") . $bulan[(int)date('m')] . date(" Y");
        $this->get_datasess = $this->db->get_where('user', ['username' =>
        $this->session->userdata('username')])->row_array();
        $this->appsetting = $this->db->get_where('db_setting', ['status_setting' => 1])->row_array();
	$timezone_all = $this->get_datasetupapp;
        date_default_timezone_set($timezone_all['timezone']);
    }

    public function fetchsetupapp()
    {
        return $this->db->get_where('db_setting')->row_array();
    }

    public function fetchdashboard()
    {
        return $this->db->get_where('user')->row_array();
    }

    public function fetchdbabsen($kode_pegawai)
    {
        $today = $this->get_today_date;
        return $this->db->get_where('db_absensi', ['kode_pegawai' => $kode_pegawai, 'tgl_absen' => $today])->row_array();
    }

    public function crudabs($typesend)
    {
        if ($typesend == 'delabs') {
            $this->db->delete('db_absensi', ['id_absen' => htmlspecialchars($this->input->post('absen_id', true))]);
        } elseif ($typesend == 'delallabs') {
            $this->db->truncate('db_absensi');
        }
    }

    public function do_absen()
    {
        $appsettings = $this->appsetting;
        $today = $this->get_today_date;
        $clocknow = date("H:i:s");
        if (strtotime($clocknow) >= strtotime($appsettings['absen_mulai']) && strtotime($clocknow) <= strtotime($appsettings['absen_mulai_to'])) {
            if ($this->db->get_where('db_absensi', ['tgl_absen' => $today, 'kode_pegawai' => $this->get_datasess['kode_pegawai']])->row_array()) {
                $data = [
                    'jam_masuk' => $clocknow
                ];
                $this->db->where('tgl_absen', $today)->where('kode_pegawai', $this->get_datasess['kode_pegawai']);
                $this->db->update('db_absensi', $data);
            } else {
                $data = [
                    'nama_pegawai' => $this->get_datasess['nama_lengkap'],
                    'kode_pegawai' => $this->get_datasess['kode_pegawai'],
                    'jam_masuk' => $clocknow,
                    'id_absen' => uniqid('absen_'),
                    'tgl_absen' => $today,
                    'keterangan_absen' => htmlspecialchars($this->input->post('ket_absen', true)),
                    'status_pegawai' => 1,
                    'maps_absen' => $appsettings['maps_use'] == TRUE ? htmlspecialchars($this->input->post('maps_absen', true)) : 'No Location'
                ];
                $this->db->insert('db_absensi', $data);
            }
        } elseif (strtotime($clocknow) > strtotime($appsettings['absen_mulai_to']) && strtotime($clocknow) >= strtotime($appsettings['absen_pulang'])) {
            if ($this->db->get_where('db_absensi', ['tgl_absen' => $today, 'kode_pegawai' => $this->get_datasess['kode_pegawai']])->row_array()) {
                $data = [
                    'jam_pulang' => $clocknow
                ];
                $this->db->where('tgl_absen', $today)->where('kode_pegawai', $this->get_datasess['kode_pegawai']);
                $this->db->update('db_absensi', $data);
            } else {
                $data = [
                    'nama_pegawai' => $this->get_datasess['nama_lengkap'],
                    'kode_pegawai' => $this->get_datasess['kode_pegawai'],
                    'jam_masuk' => $clocknow,
                    'id_absen' => uniqid('absen_'),
                    'tgl_absen' => $today,
                    'keterangan_absen' => htmlspecialchars($this->input->post('ket_absen', true)),
                    'status_pegawai' => 2,
                    'maps_absen' => $appsettings['maps_use'] == TRUE ? htmlspecialchars($this->input->post('maps_absen', true)) : 'No Location'
                ];
                $this->db->insert('db_absensi', $data);
            }
        } elseif (strtotime($clocknow) > strtotime($appsettings['absen_mulai_to']) && strtotime($clocknow) <= strtotime($appsettings['absen_pulang'])) {
            $data = [
                'nama_pegawai' => $this->get_datasess['nama_lengkap'],
                'kode_pegawai' => $this->get_datasess['kode_pegawai'],
                'jam_masuk' => $clocknow,
                'id_absen' => uniqid('absen_'),
                'tgl_absen' => $today,
                'keterangan_absen' => htmlspecialchars($this->input->post('ket_absen', true)),
                'status_pegawai' => 2,
                'maps_absen' => $appsettings['maps_use'] == TRUE ? htmlspecialchars($this->input->post('maps_absen', true)) : 'No Location'
            ];
            $this->db->insert('db_absensi', $data);
        }
    }
}
