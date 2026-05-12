<?php

namespace Modules\RTK\Http\Controllers\AdminPusat;

use App\Http\Controllers\Controller;

use Modules\RTK\Http\Requests\SurveyPeriodStoreRequest;
use Modules\RTK\Http\Requests\SurveyPeriodUpdateRequest;
use Modules\RTK\Models\RtkSurveyPeriod;
use Modules\RTK\Services\RtkSurveyPeriodService;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\PermissionMiddleware;

class RtkSurveyPeriodController extends Controller implements HasMiddleware
{
    public function __construct(
        private RtkSurveyPeriodService $surveyPeriodService
    ) {}

    public static function middleware(): array
    {
        return [
            new Middleware(PermissionMiddleware::using('survey-period-view|survey-period-create|survey-period-edit|survey-period-delete'), only: ['index']),
            new Middleware(PermissionMiddleware::using('survey-period-create'), only: ['store']),
            new Middleware(PermissionMiddleware::using('survey-period-edit'), only: ['update', 'activate', 'close']),
            new Middleware(PermissionMiddleware::using('survey-period-delete'), only: ['destroy']),
        ];
    }



    public function index()
    {
        $periods = $this->surveyPeriodService->getPaginatedPeriods();

        return view('rtk::adminPusat.survey-periods.index', compact('periods'));
    }

    public function store(SurveyPeriodStoreRequest $request)
    {
        try {
            $this->surveyPeriodService->createPeriod($request->validated());

            return redirect()->route('admin-pusat.survey-periods.index')
                ->with('success', "Periode \"{$request->nama}\" berhasil dibuat.");
        } catch (\LogicException $e) {
            return redirect()->route('admin-pusat.survey-periods.index')
                ->with('error', $e->getMessage());
        }
    }

    public function update(SurveyPeriodUpdateRequest $request, RtkSurveyPeriod $survey_period)
    {
        $this->surveyPeriodService->updatePeriod($survey_period, $request->validated());

        return redirect()->route('admin-pusat.survey-periods.index')
            ->with('success', "Periode \"{$request->nama}\" berhasil diperbarui.");
    }

    public function activate(RtkSurveyPeriod $survey_period)
    {
        try {
            $this->surveyPeriodService->activatePeriod($survey_period);

            return redirect()->route('admin-pusat.survey-periods.index')
                ->with('success', "Periode \"{$survey_period->nama}\" berhasil diaktifkan.");
        } catch (\LogicException $e) {
            return redirect()->route('admin-pusat.survey-periods.index')
                ->with('error', $e->getMessage());
        }
    }

    public function close(RtkSurveyPeriod $survey_period)
    {
        $this->surveyPeriodService->closePeriod($survey_period);

        return redirect()->route('admin-pusat.survey-periods.index')
            ->with('success', "Periode \"{$survey_period->nama}\" berhasil ditutup.");
    }

    public function destroy(RtkSurveyPeriod $survey_period)
    {
        $nama = $survey_period->nama;
        $this->surveyPeriodService->deletePeriod($survey_period);

        return redirect()->route('admin-pusat.survey-periods.index')
            ->with('success', "Periode \"{$nama}\" berhasil dihapus.");
    }
}
