<?php

namespace Modules\User\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {   
        $activities = Activity::with(['causer', 'subject'])->where('subject_type', User::class)
            ->latest()
            ->paginate(20);
        return view('user::activity', compact('activities'));
    }
}
