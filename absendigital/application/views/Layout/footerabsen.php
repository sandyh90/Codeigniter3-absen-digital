</main>
</div>
<div id="layoutAuthentication_footer">
    <footer class="py-4 bg-light mt-auto">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; <?= date("Y"); ?><a href="<?= base_url(); ?>" class="ml-1"><?= $appname = (empty($dataapp['nama_app_absensi'])) ? 'Absensi Online' : $dataapp['nama_app_absensi']; ?></a>
                    <div class="d-inline">Powered By<a href="https://github.com/sandyh90" class="ml-1">Pickedianz</a></div>
                </div>
                <div class="text-muted">
                    Page rendered in <strong>{elapsed_time}</strong> seconds.
                </div>
            </div>
        </div>
    </footer>
</div>
</div>
<script src="<?= base_url('assets'); ?>/vendor/jquery/jquery.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="<?= base_url('assets'); ?>/js/scripts.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets/'); ?>vendor/sweetalert2/sweetalert2.all.min.js"></script>
<script>
    <?php if ($dataapp['maps_use'] == 1) : ?>
        if (document.getElementById("maps-absen")) {
            window.onload = function() {
                var popup = L.popup();
                var geolocationMap = L.map("maps-absen", {
                    center: [40.731701, -73.993411],
                    zoom: 15,
                });

                L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
                    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                }).addTo(geolocationMap);

                function geolocationErrorOccurred(geolocationSupported, popup, latLng) {
                    popup.setLatLng(latLng);
                    popup.setContent(
                        geolocationSupported ?
                        "<b>Error:</b> The Geolocation service failed." :
                        "<b>Error:</b> This browser doesn't support geolocation."
                    );
                    popup.openOn(geolocationMap);
                }

                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        function(position) {
                            var latLng = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };

                            var marker = L.marker(latLng).addTo(geolocationMap);
                            geolocationMap.setView(latLng);
                            document.getElementById("location-maps").innerHTML = position.coords.latitude + ", " + position.coords.longitude;
                        },
                        function() {
                            geolocationErrorOccurred(true, popup, geolocationMap.getCenter());
                        }
                    );
                } else {
                    //No browser support geolocation service
                    geolocationErrorOccurred(false, popup, geolocationMap.getCenter());
                }
            };
        }
    <?php else : ?>
        if (document.getElementById("location-maps")) {
            document.getElementById("location-maps").innerHTML = 'No Location';
        }
    <?php endif; ?>

    $("#btn-absensi").click(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var nama_pegawai = document.getElementById("nama_pegawai").innerHTML;
        var jam_absen = document.getElementById("clocknow").innerHTML
        var maps_absen = document.getElementById("location-maps").innerHTML;
        var ket_absen = $('#ket_absen').val();

        $.ajax({
            type: "POST",
            url: '<?= base_url('ajax/absenajax'); ?>',
            data: {
                nama_pegawai: nama_pegawai,
                jam_absen: jam_absen,
                maps_absen: maps_absen,
                ket_absen: ket_absen
            }, // serializes the form's elements.
            dataType: 'json',
            beforeSend: function() {
                swal.fire({
                    imageUrl: "<?= base_url('assets'); ?>/img/ajax-loader.gif",
                    title: "Proses Absensi",
                    text: "Please wait",
                    showConfirmButton: false,
                    allowOutsideClick: false
                });
            },
            success: function(response) {
                if (response.success == true) {
                    swal.fire({
                        icon: 'success',
                        title: 'Absen Sukses',
                        text: 'Anda Telah Absen!',
                        showConfirmButton: false,
                        timer: 1500
                    });
                    location.replace("<?= base_url('instantabsen'); ?>");
                } else {
                    $("#infoabsensi").html(response.msgabsen).show().delay(3000).fadeOut();
                    swal.close()
                }
            },
            error: function() {
                swal.fire("Absen Gagal", "Ada Kesalahan Saat Absen!", "error");
            }
        });


    });
</script>
<script>
    $(document).ready(function() {
        $("#show_hide_password button").on('click', function(event) {
            event.preventDefault();
            if ($('#show_hide_password input').attr("type") == "text") {
                $('#show_hide_password input').attr('type', 'password');
                $('#show_hide_password span').addClass("fa-eye-slash");
                $('#show_hide_password span').removeClass("fa-eye");
            } else if ($('#show_hide_password input').attr("type") == "password") {
                $('#show_hide_password input').attr('type', 'text');
                $('#show_hide_password span').removeClass("fa-eye-slash");
                $('#show_hide_password span').addClass("fa-eye");
            }
        });
    });
</script>
<script>
    function currentTime() {
        var date = new Date(); /* creating object of Date class */
        var hour = date.getHours();
        var min = date.getMinutes();
        var sec = date.getSeconds();
        hour = updateTime(hour);
        min = updateTime(min);
        sec = updateTime(sec);

        var months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        var days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];

        var curWeekDay = days[date.getDay()]; // get day
        var curDay = date.getDate(); // get date
        var curMonth = months[date.getMonth()]; // get month
        var curYear = date.getFullYear(); // get year
        var date = curWeekDay + ", " + curDay + " " + curMonth + " " + curYear; // get full date
        document.getElementById("clocknow").innerText = hour + " : " + min + " : " + sec; /* adding time to the div */
        document.getElementById("datenow").innerHTML = date;
        var t = setTimeout(function() {
            currentTime()
        }, 1000); /* setting timer */
    }

    function updateTime(k) {
        if (k < 10) {
            return "0" + k;
        } else {
            return k;
        }
    }

    if (document.getElementById("scandateclock")) {
        currentTime(); /* calling currentTime() function to initiate the process */
    }
</script>
</body>

</html>