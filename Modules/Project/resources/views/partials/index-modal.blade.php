
<div id="pdfModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl h-3/4 flex flex-col">

        <div class="flex justify-between items-center p-4 border-b">
            <h3 id="modalTitle" class="text-lg font-semibold text-gray-900 truncate pr-4"></h3>
            <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600 transition-colors flex-shrink-0">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <div class="flex-1 overflow-hidden">
            <iframe id="pdfFrame" class="w-full h-full" frameborder="0"></iframe>
        </div>
    </div>
</div>