<?php

namespace App\Http\Controllers\Penerima;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\ApplicationDocument;
use App\Models\ApplicationStatusLog;
use App\Models\SawResult;
use Illuminate\Http\Request;

class LamaranPenerimaController extends Controller
{
    public function index()
    {
        $applications = Application::with('scholarship.penyalur')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('penerima.lamaran.index', compact('applications'));
    }

    public function show($id)
    {
        $application = Application::with(['scholarship.penyalur', 'scholarship.criteria'])
            ->where('user_id', auth()->id())
            ->findOrFail($id);

        $statusLogs = ApplicationStatusLog::where('application_id', $id)
            ->oldest()
            ->get();

        $sawResults = SawResult::with('criteria')
            ->where('application_id', $id)
            ->get();

        $applicationDocuments = ApplicationDocument::with('scholarshipDocument')
            ->where('application_id', $id)
            ->get();

        $totalApplicants = Application::where('scholarship_id', $application->scholarship_id)
            ->whereNotNull('saw_score')
            ->count();

        return view('penerima.lamaran.show', compact(
            'application',
            'statusLogs',
            'sawResults',
            'applicationDocuments',
            'totalApplicants'
        ));
    }
}
