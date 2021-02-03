<div class="container auth-card">
    <div class="row justify-content-center">
        <div class="col-lg-6 ">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header">
                    <h3 class="text-center font-weight-light">Scan Barcode</h3>
                </div>
                <div class="card-body">
                    <?= $this->session->flashdata('absenmsg'); ?>
                    <div class="text-center">
                        <h4>Arahkan QR Code ke kamera</h4>
                    </div>
                    <div>
                        <select class="form-control" id="camera-select"></select>
                    </div>
                    <div class="my-4 text-center">
                        <div class="well" style="position: middle;display: inline-block;">
                            <canvas width="350" height="350" id="webcodecam-canvas"></canvas>
                            <div class="scanner-laser laser-rightBottom" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-rightTop" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-leftBottom" style="opacity: 0.5;"></div>
                            <div class="scanner-laser laser-leftTop" style="opacity: 0.5;"></div>
                        </div>
                    </div>
                    <hr>
                    <div class="small mb-3 text-muted">Absen dengan metode login ke akun anda</div>
                    <a class="btn btn-primary btn-block" href="<?= base_url('login'); ?>"><span class="fas fa-fw fa-sign-in-alt mr-2"></span>Halaman Login</a>
                </div>
            </div>
        </div>
    </div>
</div>