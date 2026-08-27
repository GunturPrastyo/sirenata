<x-dashboard::layouts.dashboard title="{{ $postTest->title }} | SIRENATA">
    <div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8 font-sans min-h-screen">

        {{-- Breadcrumb Navigation --}}
        {{-- Breadcrumb Navigation --}}
        <div class="mb-6">
            <x-breadcrumb :home="route('user.dashboard')" :items="[
                [
                    'label' => data_get($course, 'course_name') ?? data_get($course, 'name'),
                    'url' => route('user.course.my-course.detail', $slug),
                ],
                ['label' => $postTest->title],
            ]" />
        </div>

        {{-- Top Header Info Card (Kalem & Profesional) --}}
        <section
            class="bg-white rounded-xl p-5 sm:p-6 mb-6 shadow-sm border border-slate-200 flex flex-col sm:flex-row items-center gap-5">
           
            <div class="text-center sm:text-left flex-1">
                <span
                    class="inline-block px-2.5 py-1 text-[10px] font-bold uppercase tracking-wider text-[#13416B] bg-[#13416B]/10 rounded-md mb-2 border border-[#13416B]/20">
                    Lembar Evaluasi
                </span>
                <h1 class="text-xl sm:text-2xl font-extrabold text-slate-800 leading-tight mb-2">
                    {{ $postTest->title }}
                </h1>
                <p class="text-sm text-slate-600 leading-relaxed max-w-4xl">
                    {{-- Menampilkan deskripsi dari DB. Jika kosong, tampilkan default text --}}
                    {{ $postTest->description ?? 'Evaluasi ini menentukan kelulusan Anda pada modul ini. Nilai ambang batas (KKM) adalah ' . $postTest->passing_score . '. Jika nilai akhir Anda memenuhi atau melebihi ambang batas tersebut, bagian materi selanjutnya akan terbuka secara otomatis.' }}
                </p>
            </div>
        </section>

        @if (isset($result))
            {{-- ======================================================= --}}
            {{-- MODE 1: TAMPILAN HASIL TES KETIKA SUDAH SELESAI --}}
            {{-- ======================================================= --}}
            <div
                class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden max-w-full mx-auto mt-8 animate-fadeIn">
                <div class="p-6 sm:p-10 text-center">

                    @if ($result->is_passed)
                        <div
                            class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center mx-auto mb-5 border border-emerald-200 shadow-sm">
                            <i class="fas fa-check-circle text-4xl"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mb-2">Selamat, Anda Lulus! 🎉</h2>
                        <p class="text-slate-600 mb-8 max-w-lg mx-auto">Anda telah memenuhi syarat kelulusan untuk
                            evaluasi ini. Akses untuk materi selanjutnya telah terbuka secara otomatis.</p>
                    @else
                        <div
                            class="w-20 h-20 bg-red-100 text-red-600 rounded-full flex items-center justify-center mx-auto mb-5 border border-red-200 shadow-sm">
                            <i class="fas fa-times-circle text-4xl"></i>
                        </div>
                        <h2 class="text-2xl sm:text-3xl font-extrabold text-slate-800 mb-2">Anda Belum Lulus</h2>
                        <p class="text-slate-600 mb-8 max-w-lg mx-auto">Nilai Anda belum mencapai ambang batas minimum
                            (KKM). Silakan pelajari kembali materi sebelumnya dan ulangi evaluasi ini.</p>
                    @endif

                    <!-- Circular Score Widget -->
                    <div class="flex justify-center mb-10">
                        <div
                            class="relative w-40 h-40 flex items-center justify-center bg-slate-50 rounded-full border-[12px] {{ $result->is_passed ? 'border-emerald-500' : 'border-red-500' }} shadow-inner">
                            <div class="text-center">
                                <span
                                    class="block text-5xl font-black {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }}">{{ $result->score }}</span>
                                <span
                                    class="block text-[10px] font-bold text-slate-400 uppercase tracking-wider mt-1">Skor
                                    Akhir</span>
                            </div>
                        </div>
                    </div>

                    <!-- Grid Rincian Detail -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Ambang Batas
                            </p>
                            <p class="text-lg font-extrabold text-slate-800">{{ $postTest->passing_score }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Status</p>
                            <p
                                class="text-lg font-extrabold {{ $result->is_passed ? 'text-emerald-600' : 'text-red-600' }}">
                                {{ $result->is_passed ? 'Lulus' : 'Gagal' }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Total Soal</p>
                            <p class="text-lg font-extrabold text-slate-800">{{ count($postTest->questions) }}</p>
                        </div>
                        <div class="bg-slate-50 p-4 rounded-xl border border-slate-100">
                            <p class="text-[10px] font-bold text-slate-500 uppercase tracking-wider mb-1">Dikerjakan
                                Pada</p>
                            <p class="text-xs font-bold text-slate-800 mt-1.5">
                                {{ \Carbon\Carbon::parse($result->updated_at)->translatedFormat('d M Y, H:i') }}</p>
                        </div>
                    </div>

                    <!-- Action Buttons (Posisi Kiri dan Kanan) -->
                    <div class="flex flex-col sm:flex-row gap-4 justify-between border-t border-slate-100 pt-8 mt-4">
                        <a href="{{ route('user.course.my-course.detail', $slug) }}"
                            class="w-full sm:w-auto px-6 py-3 rounded-lg font-bold bg-slate-100 text-slate-700 hover:bg-slate-200 transition-colors border border-slate-200 flex items-center justify-center gap-2 order-2 sm:order-1">
                            <i class="fas fa-arrow-left"></i> Kembali ke Modul
                        </a>

                        <div class="w-full sm:w-auto order-1 sm:order-2">
                            @if (!$result->is_passed)
                                <a href="?retake=1"
                                    class="w-full sm:w-auto px-6 py-3 rounded-lg font-bold bg-[#13416B] text-white hover:bg-[#0f3354] transition-colors flex items-center justify-center gap-2 shadow-sm">
                                    <i class="fas fa-redo"></i> Ulangi Tes Sekarang
                                </a>
                            @else
                                {{-- Jika sudah lulus, beri tombol opsional untuk retake --}}
                                <a href="?retake=1"
                                    class="w-full sm:w-auto px-6 py-3 rounded-lg font-bold bg-white text-[#13416B] border border-[#13416B]/30 hover:bg-[#13416B]/5 transition-colors flex items-center justify-center gap-2">
                                    <i class="fas fa-redo"></i> Kerjakan Ulang (Opsional)
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
            <div class="flex flex-col lg:flex-row gap-6 lg:gap-8 items-start" x-data="{
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
                        alert(`Kamu baru menjawab ${answeredCount} dari ${this.total} soal. Silakan lengkapi semua jawaban sebelum mengirim.`);
                        return;
                    }
                    if (confirm('Apakah Anda yakin ingin mengirimkan jawaban ujian ini? Pastikan semua jawaban sudah benar.')) {
                        this.isSubmitting = true;
                        document.getElementById('testForm').submit();
                    }
                }
            }"
                x-init="initTimer()">

                {{-- Kiri: Area Soal & Pilihan Ganda --}}
                <div
                    class="flex-1 w-full order-2 lg:order-1 bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden animate-fadeIn">

                    {{-- Mini Header: Indikator Soal & Timer --}}
                    <div class="flex justify-between items-center bg-slate-50/50 p-4 sm:p-5 border-b border-slate-200">
                        <div class="font-semibold text-slate-600 text-sm sm:text-base">
                            Soal <span class="text-[#13416B] font-bold" x-text="idx + 1"></span> dari <span
                                x-text="total"></span>
                        </div>
                        <div class="flex items-center gap-2 text-sm sm:text-base font-bold px-3 py-1.5 rounded-lg border shadow-sm transition-colors"
                            :class="timeLeft <= 60 ? 'bg-red-50 text-red-600 border-red-200 animate-pulse' :
                                'bg-white text-slate-700 border-slate-200'">
                            <i class="fas fa-clock" :class="timeLeft <= 60 ? 'text-red-500' : 'text-slate-400'"></i>
                            <span class="tracking-wider font-mono" x-text="formatTime()"></span>
                        </div>
                    </div>

                    {{-- Kontainer Soal --}}
                    <div class="p-5 sm:p-8">
                        <form id="testForm"
                            action="{{ route('user.course.test.submit', ['slug' => $slug, 'postTestId' => $postTest->id]) }}"
                            method="POST">
                            @csrf

                            @foreach ($postTest->questions as $index => $question)
                                <div x-show="idx === {{ $index }}" style="display: none;" x-cloak
                                    class="animate-fadeIn">
                                    {{-- Teks Soal --}}
                                    <div class="flex items-start gap-4 mb-6">
                                        <span
                                            class="flex items-center justify-center w-8 h-8 rounded-lg bg-[#13416B]/10 text-[#13416B] text-sm font-extrabold shrink-0 border border-[#13416B]/20 shadow-sm mt-0.5">
                                            {{ $index + 1 }}
                                        </span>
                                        <div
                                            class="text-slate-800 font-medium text-base sm:text-lg leading-relaxed prose max-w-none">
                                            {!! $question->question !!}
                                        </div>
                                    </div>

                                    {{-- Pilihan Ganda --}}
                                    <div class="space-y-3 pl-0 sm:pl-12">
                                        @foreach ($question->choices as $choice)
                                            <label
                                                class="flex items-start p-4 rounded-xl border cursor-pointer transition-all duration-200"
                                                :class="answers['{{ $question->id }}'] === '{{ $choice->id }}' ?
                                                    'bg-[#13416B]/5 border-[#13416B]/40 shadow-sm ring-1 ring-[#13416B]/30' :
                                                    'bg-white border-slate-200 hover:bg-slate-50 hover:border-slate-300'">
                                                <input type="radio" name="answers[{{ $question->id }}]"
                                                    value="{{ $choice->id }}"
                                                    x-model="answers['{{ $question->id }}']"
                                                    class="mt-1 flex-shrink-0 w-4 h-4 text-[#13416B] border-slate-300 focus:ring-[#13416B]">
                                                <span class="ml-3 flex-1 text-sm sm:text-base leading-relaxed"
                                                    :class="answers['{{ $question->id }}'] === '{{ $choice->id }}' ?
                                                        'text-[#13416B] font-bold' : 'text-slate-700'">
                                                    {{ $choice->choice }}
                                                </span>
                                            </label>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </form>

                        {{-- Kontrol Navigasi & Aksi --}}
                        <div
                            class="flex flex-col sm:flex-row gap-3 sm:gap-4 items-center justify-between mt-10 pt-6 border-t border-slate-100">
                            {{-- Prev/Next --}}
                            <div class="flex gap-2 w-full sm:w-auto">
                                <button type="button" @click="idx--" :disabled="idx === 0"
                                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white text-slate-600 border border-slate-300 px-5 py-2.5 rounded-lg font-bold disabled:opacity-50 disabled:cursor-not-allowed hover:bg-slate-50 hover:text-slate-800 transition-colors text-sm shadow-sm">
                                    <i class="fas fa-arrow-left"></i> Sebelumnya
                                </button>
                                <button type="button" @click="idx++" x-show="idx < total - 1"
                                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-[#13416B] text-white px-5 py-2.5 rounded-lg font-bold hover:bg-[#0f3354] transition-colors shadow-sm text-sm">
                                    Berikutnya <i class="fas fa-arrow-right"></i>
                                </button>
                            </div>

                            {{-- Submit --}}
                            <div class="w-full sm:w-auto mt-2 sm:mt-0">
                                <button type="button" x-show="idx === total - 1" @click="submitTest"
                                    :disabled="isSubmitting"
                                    class="w-full flex items-center justify-center gap-2 bg-emerald-600 text-white px-8 py-2.5 rounded-lg font-bold hover:bg-emerald-700 transition-colors shadow-sm text-sm disabled:opacity-70 disabled:cursor-wait">
                                    <span x-text="isSubmitting ? 'Memproses...' : 'Kirim Jawaban'"></span>
                                    <i class="fas fa-paper-plane" x-show="!isSubmitting"></i>
                                    <i class="fas fa-circle-notch fa-spin" x-show="isSubmitting"
                                        style="display: none;"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Progress Bar Bawah --}}
                        <div class="h-2 bg-slate-100 rounded-full overflow-hidden mt-8 border border-slate-200/50">
                            <div class="h-full bg-[#13416B] transition-all duration-500 ease-out"
                                :style="`width: ${((idx + 1) / total) * 100}%`"></div>
                        </div>
                    </div>
                </div>

                {{-- Kanan: Sidebar Navigasi Grid --}}
                <div class="w-full lg:w-80 shrink-0 order-1 lg:order-2 animate-fadeIn">
                    <div class="p-5 bg-white rounded-xl shadow-sm border border-slate-200 lg:sticky lg:top-24">
                        <div class="flex items-center gap-2 mb-4 pb-3 border-b border-slate-100">
                            <div class="p-1.5 bg-[#13416B]/10 text-[#13416B] rounded-md">
                                <i class="fas fa-th-large text-sm"></i>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800">Navigasi Soal</h3>
                        </div>

                        {{-- Legenda --}}
                        <div
                            class="flex justify-between text-[10px] sm:text-[11px] font-semibold text-slate-500 mb-5 bg-slate-50 p-3 rounded-lg border border-slate-100 shadow-inner">
                            <div class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded bg-[#13416B] shadow-sm"></span><span>Terjawab</span></div>
                            <div class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded bg-white border border-slate-300"></span><span>Belum</span>
                            </div>
                            <div class="flex items-center gap-1.5"><span
                                    class="w-3 h-3 rounded border-2 border-[#13416B] bg-white"></span><span>Aktif</span>
                            </div>
                        </div>

                        {{-- Papan Grid Angka --}}
                        <div class="grid grid-cols-5 gap-2">
                            @foreach ($postTest->questions as $index => $question)
                                <button type="button" @click="idx = {{ $index }}"
                                    class="h-10 w-full rounded-lg text-sm font-bold transition-all flex items-center justify-center border shadow-sm"
                                    :class="{
                                        'ring-2 ring-offset-1 ring-[#13416B] z-10': idx === {{ $index }},
                                        'bg-[#13416B] text-white border-[#13416B] opacity-90 hover:opacity-100': answers[
                                            '{{ $question->id }}'] && idx !== {{ $index }},
                                        'bg-[#13416B] text-white border-[#13416B]': answers['{{ $question->id }}'] &&
                                            idx === {{ $index }},
                                        'bg-white text-slate-600 border-slate-200 hover:bg-slate-100': !answers[
                                            '{{ $question->id }}'] && idx !== {{ $index }},
                                        'bg-[#13416B]/10 text-[#13416B] border-[#13416B]/40': !answers[
                                            '{{ $question->id }}'] && idx === {{ $index }}
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

    {{-- Animasi CSS untuk FadeIn --}}
    @push('styles')
        <style>
            .animate-fadeIn {
                animation: fadeIn 0.4s ease-in-out forwards;
            }

            @keyframes fadeIn {
                from {
                    opacity: 0;
                    transform: translateY(5px);
                }

                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
        </style>
    @endpush
</x-dashboard::layouts.dashboard>
