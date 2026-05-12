<?php

namespace Modules\Project\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Modules\Project\Enums\ProjectType;
use Modules\Project\Http\Requests\ProjectStoreRequest;
use Modules\Project\Http\Requests\ProjectUpdateRequest;
use Modules\Project\Models\Project;
use Modules\Project\Services\ProjectService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class ProjectController extends Controller implements HasMiddleware
{
    protected string $routePrefix = 'admin-pusat.project.';

    public function __construct(
        private ProjectService $projectService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('project-view|project-create|project-edit|project-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('project-view'), only: ['show']),
            new Middleware(PermissionMiddleware::using('project-create'), only: ['create', 'store']),
            new Middleware(PermissionMiddleware::using('project-edit'), only: ['edit', 'update']),
            new Middleware(PermissionMiddleware::using('project-delete'), only: ['destroy']),
        ];
    }



    public function index(Request $request)
    {
        $projects = $this->projectService->paginateFiltered(
            type: ProjectType::NASIONAL,
            search: $request->search,
            status: $request->status,
            limit: $request->get('per_page', 10)
        );
        $routePrefix = $this->routePrefix;

        return view('project::index', compact('projects', 'routePrefix'));
    }

    public function create()
    {
        $users = $this->projectService->getUsersByScope();
        $routePrefix = $this->routePrefix;
        return view('project::create', compact('users', 'routePrefix'));
    }

    public function store(ProjectStoreRequest $request)
    {
        $this->projectService->createProject($request->validated(), ProjectType::NASIONAL);

        return redirect()->route($this->routePrefix . 'index')->with('success', 'Proyek berhasil dibuat!');
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
        $users = $this->projectService->getUsersByScope();
        $routePrefix = $this->routePrefix;
        return view('project::edit', compact('project', 'users', 'routePrefix'));
    }

    public function update(ProjectUpdateRequest $request, $id)
    {
        $project = Project::findOrFail($id);
        $this->projectService->updateProject($project, $request->validated());

        return redirect()->route($this->routePrefix . 'index')->with('success', 'Proyek berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $project = Project::findOrFail($id);
        $this->projectService->deleteProject($project);

        return redirect()->route($this->routePrefix . 'index')->with('success', 'Proyek berhasil dihapus!');
    }
}
