<div class="container auth-card">
    <div class="row justify-content-center">
        <?php if ($dataapp['maps_use'] == 1) : ?>
            <div class="col-lg-6">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header">
                        <h3 class="text-center font-weight-light">Maps Absen</h3>
                    </div>
                    <div class="card-body">
                        <div id='maps-absen' style='width: 100%; height:250px;'></div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="col-lg-6 ">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header">
                    <h3 class="text-center font-weight-light">Konfirmasi Absen Scanner</h3>
                </div>
                <div class="card-body">
                    <div id="infoabsensi"></div>
                    <div id="location-maps" style="display: none;"></div>
                    <div class="text-center" id="scandateclock">
                        <h3 id="clocknow"></h3>
                        <h3 id="datenow"></h3>
                    </div>
                    <hr>
                    <dl class="row">
                        <dt class="col-sm-5">Nama Pegawai:</dt>
                        <dd class="col-sm-7" id="nama_pegawai"><?= $cfmabs['nama_lengkap'] ?></dd>
                        <dt class="col-sm-5">Kode Pegawai:</dt>
                        <dd class="col-sm-7"><?= $cfmabs['kode_pegawai'] ?></dd>
                        <dt class="col-sm-5">NPWP:</dt>
                        <dd class="col-sm-7"><?= $cfmabs['npwp'] ?></dd>
                        <dt class="col-sm-5">Bagian Shift:</dt>
                        <dd class="col-sm-7"><?= ($cfmabs['bagian_shift'] == 1) ? '<span class="badge badge-success">Full Time</span>' : (($cfmabs['bagian_shift'] == 2) ? '<span class="badge badge-warning">Part Time</span>' : '<span class="badge badge-primary">Shift Time</span>'); ?></dd>
                    </dl>
                    <hr>
                    <?= form_dropdown('ket_absen', ['Bekerja Di Kantor' => 'Bekerja Di Kantor', 'Bekerja Di Rumah / WFH' => 'Bekerja Di Rumah / WFH', 'Sakit' => 'Sakit', 'Cuti' => 'Cuti'], '', ['class' => 'form-control align-content-center my-2', 'id' => 'ket_absen']); ?>
                    <div class="text-center" id="func-absensi">
                        <p class="font-weight-bold">Status Kehadiran: <?= $statuspegawai = (empty($dbabsensi['status_pegawai'])) ? '<span class="badge badge-primary">Belum Absen</span>' : (($dbabsensi['status_pegawai'] == 1) ? '<span class="badge badge-success">Sudah Absen</span>' : '<span class="badge badge-danger">Absen Terlambat</span>'); ?></p>
                        <div id="jamabsen" class="d-flex justify-content-between">
                            <p>Waktu Datang: <?= $jammasuk = (empty($dbabsensi['jam_masuk'])) ? '00:00:00' : $dbabsensi['jam_masuk']; ?></p>
                            <p>Waktu Pulang: <?= $jammasuk = (empty($dbabsensi['jam_pulang'])) ? '00:00:00' : $dbabsensi['jam_pulang']; ?></p>
                        </div>
                    </div>
                    <div class="text-center">
                        <button class="btn btn-dark" id="btn-absensi">Absen</button>
                    </div>
                    <hr>
                    <a class="btn btn-primary btn-block" href="<?= base_url('instantabsen'); ?>"><span class="fas fa-fw fa-qrcode mr-2"></span>Kembali Ke Halaman Scanner</a>
                </div>
                <div class="card-footer">
                    <div class="d-flex align-items-center justify-content-between">
                        <div class="text-muted">Absen Datang Jam: <?= $dataapp['absen_mulai'] ?></div>
                        <div class="text-muted">Absen Pulang Jam: <?= $dataapp['absen_pulang']; ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>