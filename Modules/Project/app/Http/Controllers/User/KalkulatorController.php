<?php

namespace Modules\Project\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Project\Models\Project;
use Modules\Project\Models\ProjectRtkData;

class KalkulatorController extends Controller
{
    public function sandbox()
    {
        return view('project::kalkulator.index', [
            'mode' => 'sandbox',
            'projectId' => null,
        ]);
    }

    public function project($projectId)
    {
        $project = Project::findOrFail($projectId);
        $user = Auth::user();

        $isLeader = $project->team_leader === $user->id;
        $isMember = is_array($project->team_members) && in_array($user->id, $project->team_members);

        if (!$isLeader && !$isMember) {
            abort(403, 'Anda bukan anggota proyek ini.');
        }

        return view('project::kalkulator.index', [
            'mode' => 'project',
            'projectId' => $project->id,
        ]);
    }

    public function save(Request $request)
    {
        $projectId = $request->input('project_id');

        if (!$projectId) {
            return response()->json(['success' => false, 'error' => 'project_id diperlukan'], 400);
        }

        $project = Project::find($projectId);
        if (!$project) {
            return response()->json(['success' => false, 'error' => 'Proyek tidak ditemukan'], 404);
        }

        $user = Auth::user();
        $isLeader = $project->team_leader === $user->id;
        $isMember = is_array($project->team_members) && in_array($user->id, $project->team_members);

        if (!$isLeader && !$isMember) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
        }

        $namaDaerah = trim($request->input('nama_daerah', ''));
        $data = $request->input('data', '');

        if ($namaDaerah === '' || $data === '') {
            return response()->json(['success' => false, 'error' => 'Data tidak lengkap'], 400);
        }

        $hA = (int) $request->input('hA', 0);
        $hZ = (int) $request->input('hZ', 0);
        $pA = (int) $request->input('pA', 0);
        $pZ = (int) $request->input('pZ', 0);
        $sizeBytes = strlen($data);

        $record = ProjectRtkData::updateOrCreate(
            [
                'project_id' => $projectId,
                'tahun_hist_awal' => $hA,
                'tahun_hist_akhir' => $hZ,
                'tahun_proj_awal' => $pA,
                'tahun_proj_akhir' => $pZ,
            ],
            [
                'nama_daerah' => $namaDaerah,
                'jml_sheet' => (int) $request->input('jml_sheet', 0),
                'data' => $data,
                'size_bytes' => $sizeBytes,
            ]
        );

        return response()->json([
            'success' => true,
            'user_id' => $record->id,
            'kode_user' => (string) $projectId,
            'size_bytes' => $sizeBytes,
        ]);
    }

    public function load(Request $request)
    {
        $id = (int) $request->query('id', 0);

        if ($id <= 0) {
            return response()->json(['success' => false, 'error' => 'Parameter ?id diperlukan'], 400);
        }

        $record = ProjectRtkData::find($id);
        if (!$record) {
            return response()->json(['success' => false, 'error' => 'Data tidak ditemukan'], 404);
        }

        $project = Project::find($record->project_id);
        $user = Auth::user();
        $isLeader = $project && $project->team_leader === $user->id;
        $isMember = $project && is_array($project->team_members) && in_array($user->id, $project->team_members);

        if (!$isLeader && !$isMember) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
        }

        return response()->json([
            'success' => true,
            'user_id' => $record->id,
            'kode_user' => (string) $record->project_id,
            'nama_daerah' => $record->nama_daerah,
            'data' => $record->data,
        ]);
    }

    public function sessions(Request $request)
    {
        $user = Auth::user();

        $projectIds = Project::where('team_leader', $user->id)
            ->orWhereJsonContains('team_members', $user->id)
            ->pluck('id');

        $q = trim($request->query('q', ''));

        $query = ProjectRtkData::whereIn('project_id', $projectIds)
            ->select('id', 'project_id as kode_user', 'nama_daerah', 'tahun_hist_awal', 'tahun_hist_akhir', 'tahun_proj_awal', 'tahun_proj_akhir', 'jml_sheet', 'size_bytes', 'created_at', 'updated_at');

        if ($q) {
            $query->where(function ($sub) use ($q) {
                $sub->where('nama_daerah', 'like', "%{$q}%");
            });
        }

        $rows = $query->orderByDesc('updated_at')->limit(200)->get();

        return response()->json(['success' => true, 'users' => $rows]);
    }

    public function delete(Request $request)
    {
        $recordId = (int) ($request->input('user_id') ?? 0);

        if ($recordId <= 0) {
            return response()->json(['success' => false, 'error' => 'user_id tidak valid']);
        }

        $record = ProjectRtkData::find($recordId);
        if (!$record) {
            return response()->json(['success' => false, 'error' => "Record #{$recordId} tidak ditemukan"]);
        }

        $project = Project::find($record->project_id);
        $user = Auth::user();
        $isLeader = $project && $project->team_leader === $user->id;
        $isMember = $project && is_array($project->team_members) && in_array($user->id, $project->team_members);

        if (!$isLeader && !$isMember) {
            return response()->json(['success' => false, 'error' => 'Akses ditolak'], 403);
        }

        $record->delete();

        return response()->json([
            'success' => true,
            'deleted_id' => $recordId,
            'kode_user' => (string) $record->project_id,
            'nama_daerah' => $record->nama_daerah,
        ]);
    }
}
