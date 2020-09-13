</main>
</div>
<div id="layoutAuthentication_footer">
    <footer class="py-4 bg-light mt-auto">
        <div class="container">
            <div class="d-flex align-items-center justify-content-between small">
                <div class="text-muted">Copyright &copy; <?= date("Y"); ?><a href="<?= base_url(); ?>" class="ml-1"><?= $appname = (empty($dataapp['nama_app_absensi'])) ? 'Absensi Online' : $dataapp['nama_app_absensi']; ?></a></div>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script src="<?= base_url('assets'); ?>/vendor/jquery-easing/jquery.easing.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/datatables/dataTables.bootstrap4.min.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/webcam/webcodecamjquery.js"></script>
<script src="<?= base_url('assets'); ?>/vendor/webcam/webcodecamjs.js"></script>
<script src="<?= base_url('assets'); ?>/js/qrcodelib.js"></script>
<script src="<?= base_url('assets'); ?>/js/DecoderWorker.js"></script>
<script src="<?= base_url('assets/'); ?>vendor/sweetalert2/sweetalert2.all.min.js"></script>
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
<script>
    $("#btn-absensi").click(function(e) {

        e.preventDefault(); // avoid to execute the actual submit of the form.

        var nama_pegawai = document.getElementById("nama_pegawai").innerHTML;
        var jam_absen = document.getElementById("clocknow").innerHTML

        $.ajax({
            type: "POST",
            url: '<?= base_url('ajax/absenajax'); ?>',
            data: {
                nama_pegawai: nama_pegawai,
                jam_absen: jam_absen
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
    var arg = {
        resultFunction: function(result) {
            decoder.stop();
            var redirect = "<?= base_url('confirmabsen'); ?>";
            $.redirectPost(redirect, {
                id_pegawai: result.code
            });
        }
    };

    var decoder = $("#webcodecam-canvas").WebCodeCamJQuery(arg).data().plugin_WebCodeCamJQuery;
    /* Select environment camera if available */
    decoder.buildSelectMenu('select');
    decoder.play();
    /*  Without visible select menu
        decoder.buildSelectMenu(document.createElement('select'), 'environment|back').init(arg).play();
    */
    $('select').on('change', function() {
        decoder.stop().play();
    });

    // jquery extend function
    $.extend({
        redirectPost: function(location, args) {
            var form = '';
            $.each(args, function(key, value) {
                form += '<input type="hidden" name="' + key + '" value="' + value + '">';
            });
            $('<form action="' + location + '" method="POST">' + form + '</form>').appendTo('body').submit();
        }
    });
</script>
</body>

</html>