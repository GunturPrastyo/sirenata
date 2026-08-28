<x-dashboard::layouts.dashboard title="{{ $postTest->title }} | SIRENATA">
    {{-- PERBAIKAN RESPONSIVE: Hilangkan padding luar di HP (p-0) agar mepet, beri padding di sm ke atas --}}
    <div class="p-0 sm:p-6 lg:p-8 max-w-7xl mx-auto font-sans min-h-screen">

        {{-- Main Container --}}
        <div class="bg-white sm:rounded-2xl sm:shadow-sm sm:border border-slate-200 overflow-hidden min-h-screen sm:min-h-0">
            
            <div class="p-5 sm:p-8 lg:p-10 border-b border-slate-100">
                
                {{-- PERBAIKAN BREADCRUMB: Diubah menjadi tombol "Kembali" yang sangat ringkas dan tidak memakan tempat --}}
                <div class="mb-6 sm:mb-8">
                    <a href="{{ route('user.course.my-course.detail', $slug) }}"
                        class="group inline-flex items-center gap-3 text-sm font-medium text-slate-500 hover:text-[#13416B] transition-colors">
                        
                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center shrink-0 group-hover:bg-[#13416B]/10 transition-colors">
                            <i class="fas fa-arrow-left text-xs text-slate-600 group-hover:text-[#13416B]"></i>
                        </div>
                        
                        <div class="flex-1 min-w-0 flex flex-col sm:flex-row sm:items-center sm:gap-1.5 leading-tight">
                            <span class="text-[11px] sm:text-sm text-slate-400 mb-0.5 sm:mb-0">Kembali ke</span>
                            <span class="font-bold text-slate-700 truncate group-hover:text-[#13416B] transition-colors max-w-[200px] sm:max-w-md">
                                {{ data_get($course, 'course_name') ?? data_get($course, 'name') }}
                            </span>
                        </div>
                    </a>
                </div>

                {{-- Header Title --}}
                <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                    <div>
                        <span class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#13416B] bg-[#13416B]/10 rounded-lg mb-3 border border-[#13416B]/20">
                            Lembar Evaluasi
                        </span>
                        <h1 class="text-2xl sm:text-3xl font-extrabold text-slate-900 leading-tight mb-2">
                            {{ $postTest->title }}
                        </h1>
                        <p class="text-sm text-slate-500 leading-relaxed max-w-3xl">
                            {{ $postTest->description ?? 'Evaluasi ini menentukan kelulusan Anda. KKM: ' . $postTest->passing_score . '.' }}
                        </p>
                    </div>
                    
                    {{-- Badge KKM (Opsional, Pemanis UI Desktop) --}}
                    <div class="hidden sm:flex flex-col items-end shrink-0">
                        <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1">Ambang Batas</div>
                        <div class="text-2xl font-black text-[#13416B]">{{ $postTest->passing_score }}</div>
                    </div>
                </div>
            </div>

            <div class="p-5 sm:p-8 lg:p-10 bg-slate-50/50">
                @if (isset($result))
                    {{-- ======================================================= --}}
                    {{-- MODE 1: TAMPILAN HASIL TES KETIKA SUDAH SELESAI --}}
                    {{-- ======================================================= --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden max-w-4xl mx-auto animate-fadeIn">
                        <div class="p-6 sm:p-10 text-center">

                            @if ($result->is_passed)
                                <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-5 border border-emerald-200 shadow-sm">
                                    <i class="fas fa-check-circle text-4xl"></i>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mb-2">Selamat, Anda Lulus! 🎉</h2>
                                <p class="text-sm sm:text-base text-slate-600 mb-8 max-w-lg mx-auto">Anda memenuhi syarat kelulusan. Akses untuk materi selanjutnya telah terbuka.</p>
                            @else
                                <div class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-5 border border-red-200 shadow-sm">
                                    <i class="fas fa-times-circle text-4xl"></i>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mb-2">Anda Belum Lulus</h2>
                                <p class="text-sm sm:text-base text-slate-600 mb-8 max-w-lg mx-auto">Nilai belum mencapai minimum (KKM). Silakan pelajari ulang materi dan ulangi evaluasi.</p>
                            @endif

                            <!-- Circular Score Widget -->
                            <div class="flex justify-center mb-10 relative">
                                <div class="relative w-36 h-36 sm:w-44 sm:h-44 flex items-center justify-center bg-white rounded-full border-[10px] sm:border-[14px] {{ $result->is_passed ? 'border-emerald-500' : 'border-red-500' }} shadow-lg z-10">
                                    <div class="text-center">
                                        <span class="block text-4xl sm:text-5xl font-black {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }}">{{ $result->score }}</span>
                                        <span class="block text-[9px] sm:text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1 sm:mt-2">Skor Akhir</span>
                                    </div>
                                </div>
                                <!-- Background Glow -->
                                <div class="absolute inset-0 bg-{{ $result->is_passed ? 'emerald' : 'red' }}-400 rounded-full blur-2xl opacity-20 transform scale-110"></div>
                            </div>

                            <!-- Grid Rincian Detail -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4 mb-8 sm:mb-10">
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">KKM</p>
                                    <p class="text-base sm:text-lg font-extrabold text-slate-800">{{ $postTest->passing_score }}</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status</p>
                                    <p class="text-base sm:text-lg font-extrabold {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $result->is_passed ? 'Lulus' : 'Gagal' }}
                                    </p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Soal</p>
                                    <p class="text-base sm:text-lg font-extrabold text-slate-800">{{ count($postTest->questions) }}</p>
                                </div>
                                <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                                    <p class="text-[9px] sm:text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1.5">Waktu</p>
                                    <p class="text-[10px] sm:text-xs font-bold text-slate-800">
                                        {{ \Carbon\Carbon::parse($result->updated_at)->translatedFormat('d M, H:i') }}
                                    </p>
                                </div>
                            </div>

                            <!-- Action Buttons -->
                            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center sm:justify-between border-t border-slate-100 pt-6 sm:pt-8 mt-2">
                                <a href="{{ route('user.course.my-course.detail', $slug) }}"
                                    class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold bg-white text-slate-700 hover:bg-slate-50 transition-colors border border-slate-200 flex items-center justify-center gap-2 order-2 sm:order-1 text-sm shadow-sm">
                                    <i class="fas fa-list"></i> Kembali ke Modul
                                </a>

                                <div class="w-full sm:w-auto order-1 sm:order-2">
                                    @if (!$result->is_passed)
                                        <a href="?retake=1" class="w-full sm:w-auto px-8 py-3.5 rounded-xl font-bold bg-[#13416B] text-white hover:bg-[#0f3354] transition-colors flex items-center justify-center gap-2 shadow-sm text-sm">
                                            <i class="fas fa-redo"></i> Ulangi Evaluasi
                                        </a>
                                    @else
                                        <a href="?retake=1" class="w-full sm:w-auto px-6 py-3.5 rounded-xl font-bold bg-white text-[#13416B] border border-[#13416B]/30 hover:bg-[#13416B]/5 transition-colors flex items-center justify-center gap-2 text-sm shadow-sm">
                                            <i class="fas fa-redo"></i> Perbaiki Nilai (Opsional)
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    {{-- ======================================================= --}}
                    {{-- MODE 2: TAMPILAN PENGERJAAN TES / UJIAN BERJALAN --}}
                    {{-- ======================================================= --}}
                    <div class="flex flex-col lg:flex-row gap-5 lg:gap-8 items-start" x-data="{
                        idx: 0,
                        total: {{ count($postTest->questions) }},
                        timeLeft: {{ $postTest->duration * 60 }},
                        answers: {},
                        isSubmitting: false,
                        initTimer() {
                            let timer = setInterval(() => {
                                if (this.timeLeft > 0) {
                                    this.timeLeft--;
                                } else {
                                    clearInterval(timer);
                                    alert('Waktu ujian telah habis! Sistem akan mengirimkan jawaban Anda secara otomatis.');
                                    this.isSubmitting = true;
                                    document.getElementById('testForm').submit();
                                }
                            }, 1000);
                        },
                        formatTime() {
                            let m = Math.floor(this.timeLeft / 60).toString().padStart(2, '0');
                            let s = (this.timeLeft % 60).toString().padStart(2, '0');
                            return `${m}:${s}`;
                        },
                        submitTest() {
                            let answeredCount = Object.keys(this.answers).length;
                            if (answeredCount < this.total) {
                                alert(`Anda baru menjawab ${answeredCount} dari ${this.total} soal. Harap lengkapi semua jawaban.`);
                                return;
                            }
                            if (confirm('Yakin ingin mengirim jawaban? Pastikan semua sudah benar.')) {
                                this.isSubmitting = true;
                                document.getElementById('testForm').submit();
                            }
                        }
                    }" x-init="initTimer()">

                        {{-- Kiri: Area Soal & Pilihan Ganda --}}
                        <div class="flex-1 w-full order-2 lg:order-1 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden animate-fadeIn">

                            {{-- Mini Header: Indikator Soal & Timer --}}
                            <div class="flex justify-between items-center bg-slate-50/80 p-4 sm:p-5 border-b border-slate-100">
                                <div class="font-bold text-slate-700 text-sm sm:text-base">
                                    Soal <span class="text-[#13416B] text-lg mx-1" x-text="idx + 1"></span> / <span x-text="total" class="text-xs"></span>
                                </div>
                                <div class="flex items-center gap-2 text-sm sm:text-base font-bold px-3 py-1.5 rounded-lg border shadow-sm transition-colors"
                                    :class="timeLeft <= 60 ? 'bg-red-50 text-red-600 border-red-200 animate-pulse' : 'bg-white text-slate-700 border-slate-200'">
                                    <i class="fas fa-clock" :class="timeLeft <= 60 ? 'text-red-500' : 'text-slate-400'"></i>
                                    <span class="tracking-wider font-mono" x-text="formatTime()"></span>
                                </div>
                            </div>

                            {{-- Kontainer Soal --}}
                            <div class="p-4 sm:p-6 lg:p-8">
                                <form id="testForm"
                                    action="{{ route('user.course.test.submit', ['slug' => $slug, 'postTestId' => $postTest->id]) }}"
                                    method="POST">
                                    @csrf

                                    @foreach ($postTest->questions as $index => $question)
                                        <div x-show="idx === {{ $index }}" style="display: none;" x-cloak class="animate-fadeIn">
                                            
                                            {{-- Teks Soal --}}
                                            <div class="flex items-start gap-3 sm:gap-4 mb-6 sm:mb-8">
                                                <span class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#13416B] text-white text-sm font-extrabold shrink-0 shadow-sm mt-0.5">
                                                    {{ $index + 1 }}
                                                </span>
                                                <div class="text-slate-800 font-medium text-sm sm:text-base lg:text-lg leading-relaxed prose max-w-none">
                                                    {!! $question->question !!}
                                                </div>
                                            </div>

                                            {{-- Pilihan Ganda --}}
                                            <div class="space-y-3 sm:space-y-4 pl-0 sm:pl-12">
                                                @foreach ($question->choices as $choice)
                                                    <label class="flex items-start p-4 sm:p-5 rounded-xl border-2 cursor-pointer transition-all duration-200 group"
                                                        :class="answers['{{ $question->id }}'] === '{{ $choice->id }}' ? 'bg-[#13416B]/5 border-[#13416B]/40 shadow-sm' : 'bg-white border-slate-100 hover:border-slate-300'">
                                                        <div class="flex items-center justify-center w-5 h-5 rounded-full border-2 mt-0.5 shrink-0 transition-colors"
                                                             :class="answers['{{ $question->id }}'] === '{{ $choice->id }}' ? 'border-[#13416B] bg-[#13416B]' : 'border-slate-300 bg-white group-hover:border-slate-400'">
                                                            <div class="w-2 h-2 rounded-full bg-white" x-show="answers['{{ $question->id }}'] === '{{ $choice->id }}'"></div>
                                                        </div>
                                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $choice->id }}" x-model="answers['{{ $question->id }}']" class="hidden">
                                                        <span class="ml-3 sm:ml-4 flex-1 text-sm sm:text-base leading-relaxed"
                                                            :class="answers['{{ $question->id }}'] === '{{ $choice->id }}' ? 'text-[#13416B] font-bold' : 'text-slate-700'">
                                                            {{ $choice->choice }}
                                                        </span>
                                                    </label>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </form>

                                {{-- Kontrol Navigasi & Aksi --}}
                                <div class="flex flex-col sm:flex-row gap-3 items-center justify-between mt-8 sm:mt-10 pt-6 border-t border-slate-100">
                                    
                                    {{-- Prev/Next --}}
                                    <div class="flex gap-3 w-full sm:w-auto">
                                        <button type="button" @click="idx--" :disabled="idx === 0"
                                            class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white text-slate-600 border border-slate-200 px-5 py-3 sm:py-2.5 rounded-xl font-bold disabled:opacity-50 disabled:bg-slate-50 disabled:cursor-not-allowed hover:border-slate-300 hover:bg-slate-50 transition-colors text-xs sm:text-sm shadow-sm">
                                            <i class="fas fa-arrow-left"></i> <span class="hidden sm:inline">Sebelumnya</span>
                                        </button>
                                        <button type="button" @click="idx++" x-show="idx < total - 1"
                                            class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-[#13416B] text-white px-5 py-3 sm:py-2.5 rounded-xl font-bold hover:bg-[#0f3354] transition-colors shadow-sm text-xs sm:text-sm">
                                            <span class="hidden sm:inline">Berikutnya</span> Lanjut <i class="fas fa-arrow-right"></i>
                                        </button>
                                    </div>

                                    {{-- Submit --}}
                                    <div class="w-full sm:w-auto mt-2 sm:mt-0">
                                        <button type="button" x-show="idx === total - 1" @click="submitTest" :disabled="isSubmitting"
                                            class="w-full flex items-center justify-center gap-2 bg-emerald-600 text-white px-8 py-3.5 sm:py-3 rounded-xl font-bold hover:bg-emerald-700 transition-colors shadow-sm text-sm disabled:opacity-70 disabled:cursor-wait">
                                            <span x-text="isSubmitting ? 'Memproses...' : 'Kirim Jawaban'"></span>
                                            <i class="fas fa-paper-plane" x-show="!isSubmitting"></i>
                                            <i class="fas fa-circle-notch fa-spin" x-show="isSubmitting" style="display: none;"></i>
                                        </button>
                                    </div>
                                </div>

                                {{-- Progress Bar Bawah --}}
                                <div class="h-2 bg-slate-100 rounded-full overflow-hidden mt-6 sm:mt-8 border border-slate-200/50">
                                    <div class="h-full bg-[#13416B] transition-all duration-500 ease-out" :style="`width: ${((idx + 1) / total) * 100}%`"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: Sidebar Navigasi Grid --}}
                        <div class="w-full lg:w-80 shrink-0 order-1 lg:order-2 animate-fadeIn">
                            <div class="p-4 sm:p-5 bg-white rounded-2xl shadow-sm border border-slate-200 lg:sticky lg:top-24">
                                <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                                    <div class="p-1.5 bg-[#13416B]/10 text-[#13416B] rounded-md">
                                        <i class="fas fa-th-large text-sm"></i>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800">Navigasi Soal</h3>
                                </div>

                                {{-- Legenda (Menyamping di HP agar rapi) --}}
                                <div class="flex justify-between sm:justify-start sm:gap-4 text-[10px] font-bold text-slate-500 mb-5 bg-slate-50 p-2 sm:p-3 rounded-lg border border-slate-100">
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-[#13416B]"></span><span>Terjawab</span></div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm bg-white border border-slate-300"></span><span>Kosong</span></div>
                                    <div class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-sm border-2 border-emerald-500 bg-emerald-50"></span><span>Aktif</span></div>
                                </div>

                                {{-- Papan Grid Angka --}}
                                <div class="grid grid-cols-6 sm:grid-cols-8 lg:grid-cols-5 gap-2">
                                    @foreach ($postTest->questions as $index => $question)
                                        <button type="button" @click="idx = {{ $index }}"
                                            class="h-9 sm:h-10 w-full rounded-lg text-xs sm:text-sm font-bold transition-all flex items-center justify-center border"
                                            :class="{
                                                'border-emerald-500 bg-emerald-50 text-emerald-700 ring-2 ring-emerald-200 z-10': idx === {{ $index }},
                                                'bg-[#13416B] text-white border-[#13416B] opacity-90': answers['{{ $question->id }}'] && idx !== {{ $index }},
                                                'bg-white text-slate-500 border-slate-200': !answers['{{ $question->id }}'] && idx !== {{ $index }},
                                            }">
                                            {{ $index + 1 }}
                                        </button>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .animate-fadeIn {
                animation: fadeIn 0.4s ease-in-out forwards;
            }

            @keyframes fadeIn {
                from { opacity: 0; transform: translateY(5px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>
    @endpush
</x-dashboard::layouts.dashboard>