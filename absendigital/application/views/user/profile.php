<div class="container">
    <div class="justify-content-center">
        <h1 class="my-4"><span class="fas fa-address-card mr-2"></span>Profil Saya</h1>
        <div class="card mb-4">
            <div class="card-header text-center">
                <div class="float-right">
                    <button id="qrcode-pegawai" class="btn btn-primary" data-toggle="modal" data-target="#qrcodemodal"><span class="fas fa-qrcode mr-1"></span>QR CODE</button>
                </div>
            </div>
            <div class="card-body">
                <div class="row detail">
                    <div class="col-md-2 col-sm-4 col-6 p-2">
                        <img class="img-thumbnail" src="<?= ($user['image'] == 'default.png' ? base_url('assets/img/default-profile.png') : base_url('storage/profile/' . $user['image'])); ?>" class="card-img" style="width:100%;">
                    </div>
                    <div class="col-md-10 col-sm-8 col-6">
                        <dl class="row">
                            <dt class="col-sm-5">Nama Lengkap:</dt>
                            <dd class="col-sm-7" id="nama_pegawai"><?= $user['nama_lengkap'] ?></dd>
                            <dt class="col-sm-5">Umur:</dt>
                            <dd class="col-sm-7"><?= $user['umur'] ?><div class="ml-1 d-inline">Tahun</div>
                            </dd>
                            <dt class="col-sm-5">Instansi:</dt>
                            <dd class="col-sm-7 text-truncate"><?= $user['instansi'] ?></dd>
                            <dt class="col-sm-5">Jabatan:</dt>
                            <dd class="col-sm-7"><?= $user['jabatan'] ?></dd>
                            <dt class="col-sm-5">NPWP:</dt>
                            <dd class="col-sm-7"><?= $user['npwp'] ?></dd>
                            <dt class="col-sm-5">Tempat / Tanggal Lahir:</dt>
                            <dd class="col-sm-7"><?= $user['tempat_lahir'] ?>,<?= $user['tgl_lahir'] ?></dd>
                            <dt class="col-sm-5">Jenis Kelamin:</dt>
                            <dd class="col-sm-7"><?= $user['jenis_kelamin'] ?></dd>
                            <dt class="col-sm-5">Shift Bekerja:</dt>
                            <dd class="col-sm-7"><?= $shiftpegawai = ($user['bagian_shift'] == 1) ? '<span class="badge badge-success">Full Time</span>' : (($user['bagian_shift'] == 2) ? '<span class="badge badge-warning">Part Time</span>' : '<span class="badge badge-primary">Shift Time</span>'); ?></dd>
                            <dt class="col-sm-5">Verifikasi Akun:</dt>
                            <dd class="col-sm-7"><?= $statuspegawai = ($user['is_active'] == 1) ? '<span class="badge badge-success">Terverifikasi</span>' : '<span class="badge badge-danger">Belum Terverifikasi</span>'; ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <div class="d-flex align-items-center justify-content-between small">
                    <div class="text-muted">Id Pegawai: <?= $user['kode_pegawai'] ?></div>
                    <div class="text-muted">Akun Dibuat: <?= date('d F Y', $user['date_created']); ?></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal QR Code -->
<div class="modal fade" id="qrcodemodal" tabindex="-1" role="dialog" aria-labelledby="qrcodemodal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="qrcodemodallabel"><span class="fas fa-qrcode mr-1"></span>QR Code Pegawai</h5>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img class="img my-2" src="<?= $img_source = ($user['qr_code_image'] == 'no-qrcode.png' ? base_url('assets/img/no-qrcode.png') : base_url('storage/qrcode_pegawai/' . $user['qr_code_image'])); ?>" style="width:50%;">
                </div>
                <dl class="row">
                    <dt class="col-sm-5">Nama Lengkap:</dt>
                    <dd class="col-sm-7"><?= $user['nama_lengkap'] ?></dd>
                    <dt class="col-sm-5">NPWP:</dt>
                    <dd class="col-sm-7"><?= $user['npwp'] ?></dd>
                    <dt class="col-sm-5">Kode Pegawai:</dt>
                    <dd class="col-sm-7"><?= $user['kode_pegawai'] ?></dd>
                </dl>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>