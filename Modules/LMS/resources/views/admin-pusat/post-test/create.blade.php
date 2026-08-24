<x-dashboard::layouts.dashboard title="Buat Post Test & Soal">
    <div class="p-2 sm:p-6" x-data="postTestForm()">
        <!-- Breadcrumb Navigation -->
        <nav class="flex mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 sm:space-x-3 flex-wrap">
               
                <li>
                    <div class="flex items-center">
                        
                        <a href="{{ route('admin-pusat.management-course.courses.index') }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                           <i class="fas fa-home mr-2"></i>   Daftar Course
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="ml-1 text-sm font-medium text-slate-700 hover:text-indigo-600 md:ml-2">
                            {{ $course->name }}
                        </a>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">
                            {{ $section ? $section->name : 'Evaluasi Akhir Course' }}
                        </span>
                    </div>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-slate-400 text-xs mx-1"></i>
                        <span class="ml-1 text-sm font-medium text-slate-500 md:ml-2">Buat Soal Post Test</span>
                    </div>
                </li>
            </ol>
        </nav>

        <form action="{{ route('admin-pusat.management-course.post-tests.store') }}" method="POST" class="max-w-full mx-auto space-y-6">
            @csrf
            <!-- Hidden Inputs -->
            <input type="hidden" name="course_slug" value="{{ $course->slug }}" />
            <input type="hidden" name="course_id" value="{{ $course->id }}" />
            @if($section)
                <input type="hidden" name="course_section_id" value="{{ $section->id }}" />
            @endif

            <!-- CARD 1: INFORMASI UTAMA POST TEST -->
            <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-200 bg-slate-50">
                    <h2 class="text-base font-bold text-slate-800">1. Pengaturan Umum Post Test</h2>
                </div>
                <div class="p-6 space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Judul Post Test <span class="text-red-500">*</span></label>
                        <input type="text" name="title" required value="{{ old('title') }}" placeholder="Contoh: Ujian Evaluasi Pemahaman Bab 1" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Passing Score (KKM) <span class="text-red-500">*</span></label>
                            <input type="number" name="passing_score" required min="0" max="100" value="{{ old('passing_score', 70) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Durasi (Menit) <span class="text-red-500">*</span></label>
                            <input type="number" name="duration" required min="1" value="{{ old('duration', 30) }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm" />
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-1">Instruksi Pengerjaan (Opsional)</label>
                        <textarea name="description" rows="2" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm">{{ old('description') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- CARD 2: DAFTAR SOAL PILIHAN GANDA -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-base font-bold text-slate-800">2. Daftar Pertanyaan Pilihan Ganda</h2>
                </div>

                <!-- Looping Soal via Alpine.js -->
                <template x-for="(q, qIndex) in questions" :key="qIndex">
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden relative group">
                        <!-- Nomor & Tombol Hapus Soal -->
                        <div class="px-6 py-3 bg-slate-50 border-b border-slate-200 flex items-center justify-between">
                            <span class="text-xs font-bold text-slate-600 uppercase tracking-wider" x-text="'Pertanyaan Nomor ' + (qIndex + 1)"></span>
                            <button type="button" @click="removeQuestion(qIndex)" x-show="questions.length > 1" class="text-red-500 hover:text-red-700 text-xs font-medium flex items-center gap-1">
                                <i class="fas fa-trash-alt"></i> Hapus Soal
                            </button>
                        </div>

                        <div class="p-6 space-y-4">
                            <!-- Input Teks Soal w-full -->
                            <div>
                                <label class="block text-xs font-medium text-slate-500 mb-1">Tulis Pertanyaan <span class="text-red-500">*</span></label>
                                <textarea x-model="q.question" :name="'questions['+qIndex+'][question]'" rows="3" required placeholder="Tuliskan isi pertanyaan di sini..." class="w-full rounded-lg border border-slate-300 px-3 py-2 text-sm"></textarea>
                            </div>

                            <!-- Pilihan Ganda (Choices) w-full -->
                            <div class="space-y-3">
                                <label class="block text-xs font-medium text-slate-500">Pilihan Jawaban (Tandai lingkaran untuk jawaban yang benar) <span class="text-red-500">*</span></label>
                                
                                <template x-for="(choice, cIndex) in q.choices" :key="cIndex">
                                    <div class="flex items-start gap-3 bg-slate-50/50 p-3 rounded-lg border border-slate-100">
                                        <!-- Radio Button Kunci Jawaban -->
                                        <input type="radio" :name="'questions['+qIndex+'][correct_choice]'" :value="cIndex" x-model="q.correct_choice" required class="w-4 h-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 mt-2.5 shrink-0" title="Tandai sebagai jawaban benar">
                                        
                                        <!-- Input Teks Pilihan w-full -->
                                        <div class="w-full flex-1">
                                            <input type="text" x-model="q.choices[cIndex]" :name="'questions['+qIndex+'][choices]['+cIndex+']'" required :placeholder="'Pilihan ' + String.fromCharCode(65 + cIndex)" class="w-full rounded-md border border-slate-300 px-3 py-2 text-sm bg-white">
                                        </div>
                                        
                                        <!-- Tombol Hapus Pilihan -->
                                        <button type="button" @click="q.choices.splice(cIndex, 1)" x-show="q.choices.length > 2" class="text-slate-400 hover:text-red-500 p-2 mt-1 shrink-0">
                                            <i class="fas fa-times"></i>
                                        </button>
                                    </div>
                                </template>

                                <button type="button" @click="q.choices.push('')" class="mt-2 text-xs font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">
                                    <i class="fas fa-plus-circle"></i> Tambah Pilihan Opsi
                                </button>
                            </div>
                        </div>
                    </div>
                </template>

                <!-- Tombol Tambah Pertanyaan di Bagian Bawah -->
                <div class="pt-2">
                    <button type="button" @click="addQuestion()" class="w-full py-3 border-2 border-dashed border-indigo-200 text-indigo-600 bg-indigo-50/50 hover:bg-indigo-50 text-sm font-semibold rounded-xl transition flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-plus-circle text-lg"></i> Tambah Pertanyaan Baru
                    </button>
                </div>
            </div>

            <!-- Tombol Submit Akhir -->
            <div class="flex justify-end gap-3 pt-4 pb-12">
                <a href="{{ route('admin-pusat.management-course.courses.show', $course->slug) }}" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-300 rounded-lg hover:bg-slate-50 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 text-sm font-medium text-white bg-emerald-600 rounded-lg hover:bg-emerald-700 transition shadow-sm flex items-center gap-2">
                    <i class="fas fa-save"></i> Simpan Post Test & Soal
                </button>
            </div>
        </form>
    </div>

    <!-- Script Alpine.js -->
    @push('scripts')
    <script>
        function postTestForm() {
            return {
                questions: [
                    {
                        question: '',
                        choices: ['', '', '', ''],
                        correct_choice: 0
                    }
                ],
                addQuestion() {
                    this.questions.push({
                        question: '',
                        choices: ['', '', '', ''],
                        correct_choice: 0
                    });
                },
                removeQuestion(index) {
                    this.questions.splice(index, 1);
                }
            }
        }
    </script>
    @endpush
</x-dashboard::layouts.dashboard>