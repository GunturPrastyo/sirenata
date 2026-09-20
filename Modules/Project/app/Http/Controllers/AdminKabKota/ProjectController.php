<?php

namespace Modules\Project\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Models\Project;
use Modules\Project\Enums\ProjectType;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    protected string $routePrefix = 'admin-kab-kota.project.';

    public function index(Request $request)
    {
        $query = Project::with('leader')->latest();
        $query->where('type', ProjectType::KAB_KOTA->value); // Pastikan mengambil tipe KAB_KOTA

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
        // Alur baru: Form Create hanya untuk Nama, Tanggal, dan Upload SK.
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
            'sk_document' => 'required|file|mimes:pdf|max:5120', // SK wajib diunggah, maksimal 5MB
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
            'type' => ProjectType::KAB_KOTA->value,
            'status' => 'Draft', // Otomatis masuk antrean Pusat
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

        $users = collect(); // Kosong secara default

        // Form pengisian anggota tim HANYA muncul jika proyek sudah disetujui
        if ($project->status === 'On Progress') {
            $user = Auth::user();
            $adminScope = $user->scopeArea;

            $usersQuery = User::role('user');

            // Filter 1: Area Wilayah (Kab/Kota)
            if ($adminScope && $adminScope->regency_code) {
                $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                    $q->where('regency_code', $adminScope->regency_code);
                });
            } else {
                $usersQuery->where('id', 0); // Fallback aman
            }


            // Filter 2: Prasyarat Kursus (Bypass relasi model, tembak langsung ke tabel pivot)
            if ($project->is_prerequisite_active && $project->prerequisite_course_id) {
                $usersQuery->whereIn('id', function ($query) use ($project) {
                    $query->select('user_id')
                        ->from('course_student')
                        ->where('course_id', $project->prerequisite_course_id)
                        ->where('status', 'completed');
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

        // Validasi anggota tim HANYA jika status sudah On Progress
        if ($project->status === 'On Progress') {
            $adminScope = Auth::user()->scopeArea;

            $usersQuery = User::role('user')->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('regency_code', $adminScope?->regency_code);
            });

            // Filter 2: Prasyarat Kursus (Bypass relasi model, tembak langsung ke tabel pivot)
            if ($project->is_prerequisite_active && $project->prerequisite_course_id) {
                $usersQuery->whereIn('id', function ($query) use ($project) {
                    $query->select('user_id')
                        ->from('course_student')
                        ->where('course_id', $project->prerequisite_course_id)
                        ->where('status', 'completed');
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

        $updateData = [
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
        ];

        // Ganti SK jika Admin Kab/Kota mengunggah file baru
        if ($request->hasFile('sk_document')) {
            if ($project->sk_document) {
                Storage::disk('public')->delete($project->sk_document);
            }
            $updateData['sk_document'] = $request->file('sk_document')->store('project_sk', 'public');
        }

        // Simpan data ketua dan anggota HANYA JIKA proyek On Progress
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

        // Hapus juga file SK dari storage jika proyek dihapus
        if ($project->sk_document) {
            Storage::disk('public')->delete($project->sk_document);
        }

        $project->delete();
        ToastMagic::success('Proyek berhasil dihapus!');
        return redirect()->route($this->routePrefix . 'index');
    }
}
