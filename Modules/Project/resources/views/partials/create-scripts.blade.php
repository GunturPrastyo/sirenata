<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function () {
        // Initialize for Ketua Tim (single select with search)
        $('#teamLeader').select2({
            placeholder: 'Pilih Ketua Tim',
            allowClear: true,
            width: '100%'
        });

        // Initialize for Anggota Tim (multiple select with search)
        $('#teamMembers').select2({
            placeholder: 'Pilih Anggota Tim (bisa lebih dari satu)',
            allowClear: true,
            width: '100%',
            closeOnSelect: false,
            // maximumSelectionLength: 10 // Optional, based on creating HTML logic this was present in one but not all
        });

        // Set height for Select2 container
        $('.select2-selection--multiple').css('min-height', '40px');
    });

    // Validasi form manual jika diperlukan, tapi HTML5 required attribute sudah menangani basic validation.
    // Kita biarkan form submit secara default ke action POST.
</script>