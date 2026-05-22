<?php

namespace Modules\LandingPage\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\MasterData\Models\Province;
use Modules\MasterData\Models\Regency;
use Modules\LMS\Models\Course;
use Modules\LMS\Models\SectionContent;
use Modules\RTK\Models\RencanaTenagaKerja;

class HomeController extends Controller
{
    /**
     * Display the landing page with dynamic statistics.
     */
    public function index()
    {
        $stats = [
            'provinces'  => Province::count(),
            'regencies'  => Regency::count(),
            'courses'    => Course::count(),
            'rtk'        => RencanaTenagaKerja::count(),
        ];

        $courses = Course::with(['category', 'sections.contents'])
            ->latest()
            ->take(4)
            ->get();

        return view('landingpage::index', compact('stats', 'courses'));
    }
}
