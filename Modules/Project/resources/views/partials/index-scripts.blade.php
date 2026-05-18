<script>
    function lihatProyek(proyekName) {
        const modal = document.getElementById('pdfModal');
        const modalTitle = document.getElementById('modalTitle');
        const pdfFrame = document.getElementById('pdfFrame');

        modalTitle.textContent = proyekName;
        pdfFrame.src = '/assets/UU Nomor 13 Tahun 2003.pdf';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function editProyek(id, proyekName) {
        alert(`Edit Proyek:\nID: ${id}\nNama: ${proyekName}\n\nFungsi ini akan mengarah ke halaman edit.`);
    }

    function closeModal() {
        const modal = document.getElementById('pdfModal');
        const pdfFrame = document.getElementById('pdfFrame');

        modal.classList.add('hidden');
        pdfFrame.src = '';
        document.body.style.overflow = 'auto';
    }
</script>