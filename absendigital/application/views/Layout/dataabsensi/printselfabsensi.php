<div class="container">
    <div class="jumbotron shadow-lg">
        <div class="text-center">
            <img src="<?= (empty($dataapp['logo_instansi'])) ? FCPATH . 'assets/img/clock-image.png' : (($dataapp['logo_instansi'] == 'default-logo.png') ? FCPATH . 'assets/img/clock-image.png' : FCPATH . 'storage/setting/' . $dataapp['logo_instansi']); ?>" style="width:20%;">
            <h3>
                <?= (empty($dataapp['nama_instansi'])) ? '[Nama Instansi Belum Disetting]' : $dataapp['nama_instansi']; ?>
            </h3>
        </div>
    </div>
    <p class="my-2">Dibawah Ini Merupakan Data Absensi Yang Telah Terdata:</p>
    <div class="row detail">
        <div class="col-md-10 col-sm-8 col-6">
            <dl class="row">
                <dt class="col-sm-5">Nama Pegawai:</dt>
                <dd class="col-sm-7"><?= $dataabsensi['nama_pegawai'] ?></dd>
                <dt class="col-sm-5">Tanggal Absen:</dt>
                <dd class="col-sm-7"><?= $dataabsensi['tgl_absen'] ?></dd>
                <dt class="col-sm-5">Waktu Datang:</dt>
                <dd class="col-sm-7"><?= $dataabsensi['jam_masuk'] ?></dd>
                <dt class="col-sm-5">Waktu Pulang:</dt>
                <dd class="col-sm-7"><?= (empty($dataabsensi['jam_pulang'])) ? 'Belum Absen Pulang' : $dataabsensi['jam_pulang']; ?></dd>
                <dt class="col-sm-5">Status Kehadiran:</dt>
                <dd class="col-sm-7"><?= ($dataabsensi['status_pegawai'] == 1) ? 'Sudah Absen' : (($dataabsensi['status_pegawai'] == 2) ? 'Absen Terlambat' : 'Belum Absen'); ?></dd>
                <dt class="col-sm-5">Keterangan Absen:</dt>
                <dd class="col-sm-7"><?= $dataabsensi['keterangan_absen'] ?></dd>
                <dt class="col-sm-5">Titik Lokasi Maps:</dt>
                <dd class="col-sm-7"><?= (empty($dataabsensi['maps_absen'])) ? 'Lokasi Tidak Ditemukan' : (($dataabsensi['maps_absen'] == 'No Location') ? 'Lokasi Tidak Ditemukan' : $dataabsensi['maps_absen']); ?></dd>
            </dl>
        </div>
    </div>
    <div class="text-right">
        <p>Atas Nama.</p>
        <p><?= $dataabsensi['nama_pegawai'] ?></p>
    </div>
    <div class="small">
        PDF was generated on <?= date("Y-m-d H:i:s"); ?>
    </div>
</div>