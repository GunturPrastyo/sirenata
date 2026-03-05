<script>
    // Lihat proyek function - opens modal with PDF
    function lihatProyek(proyekName) {
        const modal = document.getElementById('pdfModal');
        const modalTitle = document.getElementById('modalTitle');
        const pdfFrame = document.getElementById('pdfFrame');

        modalTitle.textContent = proyekName;
        // Assuming assets are accessible via relative path or absolute. 
        pdfFrame.src = '/assets/UU Nomor 13 Tahun 2003.pdf';
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    // Edit proyek function (Placeholder if not using native href)
    function editProyek(id, proyekName) {
        alert(`Edit Proyek:\nID: ${id}\nNama: ${proyekName}\n\nFungsi ini akan mengarah ke halaman edit.`);
    }

    // Close modal function
    function closeModal() {
        const modal = document.getElementById('pdfModal');
        const pdfFrame = document.getElementById('pdfFrame');

        modal.classList.add('hidden');
        pdfFrame.src = '';
        document.body.style.overflow = 'auto';
    }
</script>