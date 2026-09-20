<?php

namespace Modules\Project\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Modules\Project\Models\Project;
use Modules\Project\Enums\ProjectType;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Maatwebsite\Excel\Facades\Excel;
use Modules\Project\Exports\ProjectExport;

class ProjectController extends Controller
{
    protected string $routePrefix = 'admin-pusat.project.';

    public function export(Request $request)
    {
        $filename = 'Daftar Proyek' . '-' . now()->format('Y-m-d') . '.xlsx';
        return Excel::download(
            new ProjectExport(
                search: $request->string('search')->toString() ?: null,
                status: $request->string('status')->toString() ?: null,
            ),
            $filename
        );
    }

    public function index(Request $request)
    {
        $query = Project::with(['leader', 'creator.scopeArea.province', 'creator.scopeArea.regency'])->latest();

        // Pisahkan antrean Draft daerah dari daftar monitoring proyek yang sudah berjalan.
        if ($request->status === 'Draft') {
            $query->whereIn('type', [ProjectType::PROVINSI->value, ProjectType::KAB_KOTA->value])
                ->where('status', 'Draft');
        } else {
            $query->where(function ($projectQuery) {
                $projectQuery->where('type', ProjectType::NASIONAL->value)
                    ->orWhere(function ($regionalQuery) {
                        $regionalQuery->whereIn('type', [ProjectType::PROVINSI->value, ProjectType::KAB_KOTA->value])
                            ->where('status', '!=', 'Draft');
                    });
            });

            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
        }

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $projects = $query->paginate($request->get('per_page', 10))->withQueryString();
        $routePrefix = $this->routePrefix;

        return view('project::index', compact('projects', 'routePrefix'));
    }

    public function create()
    {
        $users = User::role('user')->where(function ($query) {
            $query->doesntHave('scopeArea')
                ->orWhereHas('scopeArea', function ($q) {
                    $q->whereNull('province_code')->whereNull('regency_code');
                });
        })->get();

        $routePrefix = $this->routePrefix;
        return view('project::create', compact('users', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $allowedUserIds = User::role('user')->where(function ($query) {
            $query->doesntHave('scopeArea')
                ->orWhereHas('scopeArea', function ($q) {
                    $q->whereNull('province_code')->whereNull('regency_code');
                });
        })->pluck('id')->toArray();

        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => [
                'required',
                'exists:users,id',
                \Illuminate\Validation\Rule::in($allowedUserIds)
            ],
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => [
                'exists:users,id',
                \Illuminate\Validation\Rule::in($allowedUserIds)
            ],
        ]);

        Project::create([
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
            'created_by' => Auth::id(),
            'team_leader' => $request->teamLeader,
            'team_members' => $request->teamMembers,
            'type' => ProjectType::NASIONAL->value,
            'status' => 'On Progress',
        ]);

        ToastMagic::success('Proyek berhasil dibuat!');
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

        // KUNCI ROLE: Jika proyek ini milik Provinsi atau Kab/Kota, 
        // langsung "lempar" Admin Pusat ke halaman khusus Prasyarat!
        if (in_array($project->type, [ProjectType::PROVINSI->value, ProjectType::KAB_KOTA->value])) {
            return redirect()->route($this->routePrefix . 'prerequisite', $project->id);
        }

        // Jika ini Proyek Nasional (milik Pusat sendiri), buka form edit normal
        $users = User::role('user')->where(function ($query) {
            $query->doesntHave('scopeArea')
                ->orWhereHas('scopeArea', function ($q) {
                    $q->whereNull('province_code')->whereNull('regency_code');
                });
        })->get();

        $routePrefix = $this->routePrefix;
        return view('project::edit', compact('project', 'users', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $allowedUserIds = User::role('user')->where(function ($query) {
            $query->doesntHave('scopeArea')
                ->orWhereHas('scopeArea', function ($q) {
                    $q->whereNull('province_code')->whereNull('regency_code');
                });
        })->pluck('id')->toArray();

        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => [
                'required',
                'exists:users,id',
                \Illuminate\Validation\Rule::in($allowedUserIds)
            ],
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => [
                'exists:users,id',
                \Illuminate\Validation\Rule::in($allowedUserIds)
            ],
        ]);

        $project->update([
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
            'team_leader' => $request->teamLeader,
            'team_members' => $request->teamMembers,
        ]);

        ToastMagic::success('Proyek berhasil diperbarui!');
        return redirect()->route($this->routePrefix . 'index');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $project->delete();
        ToastMagic::success('Proyek berhasil dihapus!');
        return redirect()->route($this->routePrefix . 'index');
    }

    /**
     * Menampilkan halaman persetujuan dan pengaturan prasyarat (Khusus Pusat)
     */
    public function prerequisite($id)
    {
        $project = Project::findOrFail($id);

        // Ambil semua daftar kursus dari LMS untuk dijadikan pilihan prasyarat
        $courses = \Modules\LMS\Models\Course::select('id', 'name')->orderBy('name')->get();

        $routePrefix = $this->routePrefix;

        return view('project::admin-pusat.prerequisite', compact('project', 'courses', 'routePrefix'));
    }

    /**
     * Menyimpan pengaturan prasyarat dan mengubah status proyek
     */
    public function updatePrerequisite(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'prerequisite_course_ids' => 'nullable|array|required_if:is_prerequisite_active,1',
            'prerequisite_course_ids.*' => 'exists:courses,id',
            'status' => 'required|in:Draft,On Progress,Completed',
        ]);

        $prerequisiteCourseIds = $request->input('prerequisite_course_ids', []);

        // Simpan prasyarat dan status (Setuju / Tolak)
        $project->update([
            'prerequisite_course_id' => $prerequisiteCourseIds[0] ?? null,
            'prerequisite_course_ids' => $prerequisiteCourseIds ?: null,
            'is_prerequisite_active' => $request->has('is_prerequisite_active'),
            'status' => $request->status, // Admin Pusat mengubah dari Draft menjadi On Progress
        ]);

        ToastMagic::success('Prasyarat kursus dan persetujuan proyek berhasil diperbarui!');
        return redirect()->route($this->routePrefix . 'index');
    }
}
