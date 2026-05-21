<?php

namespace Modules\Project\Models;

use Illuminate\Database\Eloquent\Model;

class ProjectRtkData extends Model
{
    protected $table = 'project_rtk_data';

    protected $fillable = [
        'project_id',
        'nama_daerah',
        'tahun_hist_awal',
        'tahun_hist_akhir',
        'tahun_proj_awal',
        'tahun_proj_akhir',
        'jml_sheet',
        'data',
        'size_bytes',
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
}
