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
            <dd class="col-sm-7"><?= ($dataabsensi['status_pegawai'] == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : (($dataabsensi['status_pegawai'] == 2) ? '<span class="badge badge-danger">Absen Terlambat</span>' : '<span class="badge badge-primary">Belum Absen</span>'); ?></dd>
        </dl>
    </div>
</div>