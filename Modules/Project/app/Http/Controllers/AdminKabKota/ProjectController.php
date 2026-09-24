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
    protected string $projectScope = 'daerah'; // Added: Scope default untuk Admin Kab/Kota

    public function index(Request $request)
    {
        $query = Project::with('leader')->latest();
        $query->where('type', ProjectType::KAB_KOTA->value);

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->paginate($request->get('per_page', 10))->withQueryString();
        $routePrefix = $this->routePrefix;
        $projectScope = $this->projectScope;

        return view('project::index', compact('projects', 'routePrefix', 'projectScope'));
    }

    public function create()
    {
        $routePrefix = $this->routePrefix;
        $projectScope = $this->projectScope; // Added: Diteruskan ke view create.blade.php

        return view('project::create', compact('routePrefix', 'projectScope'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'duration' => 'nullable|integer',
            'sk_document' => 'required|file|mimes:pdf|max:5120',
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
            'created_by' => Auth::id(),
            'type' => ProjectType::KAB_KOTA->value,
            'status' => 'Draft',
        ]);

        ToastMagic::success('Draft proyek berhasil dibuat! Menunggu persetujuan Pusat.');
        
        // Added: Menambahkan parameter type pada redirect
        return redirect()->route($this->routePrefix . 'index', ['type' => $this->projectScope]);
    }

    public function show($id)
    {
        $project = Project::with(['leader'])->findOrFail($id);
        $routePrefix = $this->routePrefix;
        $projectScope = $this->projectScope; // Added: Diteruskan ke view show

        return view('project::show', compact('project', 'routePrefix', 'projectScope'));
    }

    public function edit($id)
    {
        $project = Project::findOrFail($id);
        $routePrefix = $this->routePrefix;
        $projectScope = $this->projectScope; // Added: Diteruskan ke view edit

        $users = collect();

        if ($project->status === 'On Progress') {
            $user = Auth::user();
            $adminScope = $user->scopeArea;

            $usersQuery = User::role('user');

            if ($adminScope && $adminScope->regency_code) {
                $usersQuery->whereHas('scopeArea', function ($q) use ($adminScope) {
                    $q->where('regency_code', $adminScope->regency_code);
                });
            } else {
                $usersQuery->where('id', 0);
            }

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

        return view('project::edit', compact('project', 'users', 'routePrefix', 'projectScope'));
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

        if ($project->status === 'On Progress') {
            $adminScope = Auth::user()->scopeArea;

            $usersQuery = User::role('user')->whereHas('scopeArea', function ($q) use ($adminScope) {
                $q->where('regency_code', $adminScope?->regency_code);
            });

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

        $updateData = [
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
        ];

        if ($request->hasFile('sk_document')) {
            if ($project->sk_document) {
                Storage::disk('public')->delete($project->sk_document);
            }
            $updateData['sk_document'] = $request->file('sk_document')->store('project_sk', 'public');
        }

        if ($project->status === 'On Progress') {
            $updateData['team_leader'] = $request->teamLeader;
            $updateData['team_members'] = $request->teamMembers;
        }

        $project->update($updateData);

        ToastMagic::success('Proyek berhasil diperbarui!');
        
        return redirect()->route($this->routePrefix . 'index', ['type' => $this->projectScope]);
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);

        if ($project->sk_document) {
            Storage::disk('public')->delete($project->sk_document);
        }

        $project->delete();
        ToastMagic::success('Proyek berhasil dihapus!');
        
        return redirect()->route($this->routePrefix . 'index', ['type' => $this->projectScope]);
    }
}