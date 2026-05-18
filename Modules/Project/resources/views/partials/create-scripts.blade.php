<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        $('#teamLeader').select2({
            placeholder: 'Pilih Ketua Tim',
            allowClear: true,
            width: '100%'
        });

        $('#teamMembers').select2({
            placeholder: 'Pilih Anggota Tim (bisa lebih dari satu)',
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
        });

        $('.select2-selection--multiple').css('min-height', '40px');
    });
</script>