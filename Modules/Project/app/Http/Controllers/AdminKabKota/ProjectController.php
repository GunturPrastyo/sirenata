<?php

namespace Modules\Project\Http\Controllers\AdminKabKota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Models\Project;
use Modules\Project\Enums\ProjectType;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class ProjectController extends Controller
{
    protected string $routePrefix = 'admin-kab-kota.project.';

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

        return view('project::index', compact('projects', 'routePrefix'));
    }

    public function create()
    {
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
        $users = $usersQuery->get();
        
        $routePrefix = $this->routePrefix;
        return view('project::create', compact('users', 'routePrefix'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => 'required|exists:users,id',
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => 'exists:users,id',
        ]);

        Project::create([
            'name' => $request->proyekName,
            'start_date' => $request->startDate,
            'end_date' => $request->endDate,
            'duration' => $request->duration,
            'team_leader' => $request->teamLeader,
            'team_members' => $request->teamMembers,
            'type' => ProjectType::KAB_KOTA->value,
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
        $users = $usersQuery->get();

        $routePrefix = $this->routePrefix;
        return view('project::edit', compact('project', 'users', 'routePrefix'));
    }

    public function update(Request $request, $id)
    {
        $project = Project::findOrFail($id);

        $request->validate([
            'proyekName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'duration' => 'nullable|integer',
            'teamLeader' => 'required|exists:users,id',
            'teamMembers' => 'nullable|array',
            'teamMembers.*' => 'exists:users,id',
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
}
