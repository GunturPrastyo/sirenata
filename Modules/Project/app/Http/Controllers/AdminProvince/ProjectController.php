<?php

namespace Modules\Project\Http\Controllers\AdminProvince;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Models\Project;
use Modules\Project\Enums\ProjectType;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage; // Tambahkan ini untuk handle upload SK

class ProjectController extends Controller
{
    protected string $routePrefix = 'admin-province.project.';

    // Asumsikan Anda sudah meng-inject ProjectService jika ingin pakai service,
    // Di sini saya pakai query manual agar sejalan dengan file lama Anda.

    public function index(Request $request)
    {
        $query = Project::with('leader')->latest();
        $query->where('type', ProjectType::PROVINSI->value);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->paginate($request->get('per_page', 10))->withQueryString();
        $routePrefix = $this->routePrefix;

        return view('project::index', compact('projects', 'routePrefix'));
    }

    public function create()
    {
        // Di alur baru, Create hanya buat nama & tanggal. 
        // Ketua & Anggota TIDAK diisi di sini, jadi kita tidak perlu ambil data $users.
        $routePrefix = $this->routePrefix;
        return view('project::create', compact('routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'duration' => 'nullable|integer',
            'sk_document' => 'required|file|mimes:pdf|max:5120', // Wajib upload SK (Max 5MB)
        ]);

        $skPath = null;
        if ($request->hasFile('sk_document')) {
            $skPath = $request->file('sk_document')->store('project_sk', 'public');
        }

        Project::create([
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
            'sk_document' => $skPath,
            'type' => ProjectType::PROVINSI->value,
            'status' => 'Draft', // ALUR BARU: Otomatis Draft, menunggu pusat!
            // team_leader dan team_members dibiarkan kosong/null
        ]);

        ToastMagic::success('Draft proyek berhasil dibuat! Menunggu persetujuan Pusat.');
        return redirect()->route($this->routePrefix . 'index');
    }

    public function show($id)
    {
        $project = Project::with(['leader'])->findOrFail($id);
        $routePrefix = $this->routePrefix;
        return view('project::show', compact('project', 'routePrefix'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $routePrefix = $this->routePrefix;

        $users = collect(); // Default kosong

        // HANYA ambil data users jika proyek sudah disetujui pusat (On Progress)
        if ($project->status === 'On Progress') {
            $user = Auth::user();
            $adminScope = $user->scopeArea;

            $usersQuery = User::role('user');

            // Filter 1: Area Wilayah (Provinsi)
            if ($adminScope && $adminScope->province_code) {
                $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                    $q->where('province_code', $adminScope->province_code)
                        ->whereNull('regency_code');
                });
            } else {
                $usersQuery->where('id', 0); // Fallback aman
            }

            // Filter 2: Prasyarat Kursus (Bypass relasi model, tembak langsung ke tabel pivot)
            $prerequisiteCourseIds = $project->prerequisiteCourseIds();
            if ($project->is_prerequisite_active && $prerequisiteCourseIds) {
                $usersQuery->whereIn('id', function ($query) use ($project) {
                    $query->select('user_id')
                        ->from('course_student')
                        ->whereIn('course_id', $project->prerequisiteCourseIds())
                        ->where('progress', '>=', 100)
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(DISTINCT course_id) = ?', [count($project->prerequisiteCourseIds())]);
                });
            }

            $users = $usersQuery->get();
        }

        return view('project::edit', compact('project', 'users', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $rules = [
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'duration' => 'nullable|integer',
            'sk_document' => 'nullable|file|mimes:pdf|max:5120',
        ];

        // Jika proyek sudah On Progress, maka validasi Ketua Tim berjalan
        if ($project->status === 'On Progress') {
            $adminScope = Auth::user()->scopeArea;

            $usersQuery = User::role('user')->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('province_code', $adminScope?->province_code)
                    ->whereNull('regency_code');
            });

            // Filter 2: Prasyarat Kursus (Bypass relasi model, tembak langsung ke tabel pivot)
            $prerequisiteCourseIds = $project->prerequisiteCourseIds();
            if ($project->is_prerequisite_active && $prerequisiteCourseIds) {
                $usersQuery->whereIn('id', function ($query) use ($project) {
                    $query->select('user_id')
                        ->from('course_student')
                        ->whereIn('course_id', $project->prerequisiteCourseIds())
                        ->where('progress', '>=', 100)
                        ->groupBy('user_id')
                        ->havingRaw('COUNT(DISTINCT course_id) = ?', [count($project->prerequisiteCourseIds())]);
                });
            }

            $allowedUserIds = $usersQuery->pluck('id')->toArray();

            $rules['teamLeader'] = [
                'required',
                'exists:users,id',
                \Illuminate\Validation\Rule::in($allowedUserIds)
            ];
            $rules['teamMembers'] = 'nullable|array';
            $rules['teamMembers.*'] = [
                'exists:users,id',
                \Illuminate\Validation\Rule::in($allowedUserIds)
            ];
        }

        $request->validate($rules);

        // Update data dasar
        $updateData = [
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
        ];

        // Update SK jika ada file baru yang diunggah
        if ($request->hasFile('sk_document')) {
            if ($project->sk_document) {
                Storage::disk('public')->delete($project->sk_document);
            }
            $updateData['sk_document'] = $request->file('sk_document')->store('project_sk', 'public');
        }

        // Update anggota tim HANYA JIKA proyek sudah On Progress
        if ($project->status === 'On Progress') {
            $updateData['team_leader'] = $request->teamLeader;
            $updateData['team_members'] = $request->teamMembers;
        }

        $project->update($updateData);

        ToastMagic::success('Proyek berhasil diperbarui!');
        return redirect()->route($this->routePrefix . 'index');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        if ($project->sk_document) {
            Storage::disk('public')->delete($project->sk_document);
        }
        $project->delete();
        ToastMagic::success('Proyek berhasil dihapus!');
        return redirect()->route($this->routePrefix . 'index');
    }
}
