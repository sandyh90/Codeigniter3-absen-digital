<div class="container">
    <div class="jumbotron shadow-lg">
        <div class="text-center">
            <img src="<?= (empty($dataapp['logo_instansi'])) ? FCPATH . 'assets/img/clock-image.png' : (($dataapp['logo_instansi'] == 'default-logo.png') ? FCPATH . 'assets/img/clock-image.png' : FCPATH . 'storage/setting/' . $dataapp['logo_instansi']); ?>" style="width:20%;">
            <h3>
                <?= (empty($dataapp['nama_instansi'])) ? '[Nama Instansi Belum Disetting]' : $dataapp['nama_instansi']; ?>
            </h3>
        </div>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th scope="col">No</th>
                <th scope="col">Nama Pegawai</th>
                <th scope="col">Tanggal Absen</th>
                <th scope="col">Jam Datang</th>
                <th scope="col">Jam Pulang</th>
                <th scope="col">Status Kehadiran</th>
                <th scope="col">Keterangan Absen</th>
                <th scope="col">Titik Lokasi Maps</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $no = 1;
            foreach ($dataabsensi as $absen) : ?>
                <tr>
                    <th scope="row"><?= $no++; ?></th>
                    <td><?= $absen->nama_pegawai; ?></td>
                    <td><?= $absen->tgl_absen; ?></td>
                    <td><?= $absen->jam_masuk; ?></td>
                    <td><?= (empty($absen->jam_pulang)) ? 'Belum Absen Pulang' : $absen->jam_pulang; ?></td>
                    <td><?= ($absen->status_pegawai == 1) ? 'Sudah Absen' : (($absen->status_pegawai == 2) ? 'Absen Terlambat' : 'Belum Absen'); ?></td>
                    <td><?= $absen->keterangan_absen; ?></td>
                    <td><?= (empty($absen->maps_absen)) ? 'Lokasi Tidak Ditemukan' : (($absen->maps_absen == 'No Location') ? 'Lokasi Tidak Ditemukan' : $absen->maps_absen); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="small">
        PDF was generated on <?= date("Y-m-d H:i:s"); ?>
    </div>
</div>