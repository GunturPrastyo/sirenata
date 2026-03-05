<?php

namespace Modules\Faq\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Faq\Models\Faq;
use App\Models\User;

class FaqDatabaseSeeder extends Seeder
{
    /**
     * Seed structured FAQ data for testing visibility per user level.
     */
    public function run()
    {
        $admin = User::whereHas('roles', function ($q) {
            $q->where('name', 'admin-pusat');
        })->first();

        if (!$admin) {
            $this->command->error('No Admin Pusat user found. Please seed users first.');
            return;
        }

        $this->command->info('Seeding structured FAQ data...');

        $pusatFaqs = [
            ['question' => '[Pusat] Bagaimana cara login ke sistem?', 'answer' => 'Gunakan email dan password yang telah didaftarkan oleh Admin.'],
            ['question' => '[Pusat] Bagaimana cara reset password?', 'answer' => 'Klik "Lupa Password" di halaman login, lalu ikuti instruksi yang dikirim ke email Anda.'],
            ['question' => '[Pusat] Apa itu E-Learning SIRENATA?', 'answer' => 'SIRENATA adalah platform e-learning untuk pelatihan kerja di seluruh Indonesia.'],
        ];

        foreach ($pusatFaqs as $data) {
            Faq::create([
                'question' => $data['question'],
                'answer' => $data['answer'],
                'level' => 'pusat',
                'created_by' => $admin->id,
            ]);
        }

        $provFaqs = [
            ['question' => '[Provinsi] Bagaimana cara mendaftarkan instansi baru di provinsi saya?', 'answer' => 'Silakan hubungi admin provinsi masing-class melalui menu Bantuan.'],
            ['question' => '[Provinsi] Kapan jadwal rekapitulasi data tingkat provinsi?', 'answer' => 'Rekapitulasi data tingkat provinsi dilakukan setiap akhir bulan.'],
            ['question' => '[Provinsi] Apakah ada pelatihan khusus Admin Provinsi?', 'answer' => 'Ya, pelatihan khusus Admin Provinsi diadakan setiap kuartal oleh pusat.'],
        ];

        foreach ($provFaqs as $data) {
            Faq::create([
                'question' => $data['question'],
                'answer' => $data['answer'],
                'level' => 'provinsi',
                'created_by' => $admin->id,
            ]);
        }

        $kabKotaFaqs = [
            ['question' => '[Kab/Kota] Dimana saya bisa melihat daftar peserta di daerah saya?', 'answer' => 'Anda bisa melihatnya di menu Peserta pada Dashboard Kab/Kota.'],
            ['question' => '[Kab/Kota] Bagaimana cara reset wilayah penugasan peserta?', 'answer' => 'Fitur ini hanya bisa dilakukan oleh Admin Provinsi atau Pusat.'],
            ['question' => '[Kab/Kota] Kapan pendaftaran pelatihan tingkat Kabupaten/Kota dibuka?', 'answer' => 'Pendaftaran dibuka secara serentak setiap awal bulan melaui website resmi.'],
        ];

        foreach ($kabKotaFaqs as $data) {
            Faq::create([
                'question' => $data['question'],
                'answer' => $data['answer'],
                'level' => 'kab_kota',
                'created_by' => $admin->id,
            ]);
        }

        $this->command->info('Seeded Level-based FAQ data:');
        $this->command->info('  - 3 Pusat');
        $this->command->info('  - 3 Provinsi');
        $this->command->info('  - 3 Kab/Kota');
        $this->command->info('Total: 9 FAQs');
    }
}
