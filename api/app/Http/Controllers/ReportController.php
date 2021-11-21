<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getAll() {
        $reports = Report::with('participant.person')
        ->get();
        return response()->json($reports);
    }

    public function getById($id) {
        $report = Report::find($id);
        return response()->json($report);
    }

    public function getByParticipantId($id) {
        $reports = Report::select('reports.id', 'reports.report')
        ->join('participants', 'participants.report_id', '=', 'reports.id')
        ->where('participants.person_id', $id)
        ->get();

        return response()->json($reports);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'title' => 'required|string',
            'report' => 'required|string',
            'humor' => 'required|string',
            'type' => 'required|in:personal,daily',
            'persons_ids' => 'required|array'
        ]);

        $data = $request->all();
        $report = new Report();

        $report->title = $data['title'];
        $report->report = $data['report'];
        $report->humor = $data['humor'];
        $report->type = $data['type'];

        $report->save();

        foreach ($data['persons_ids'] as $person_id) {
            $participant = new Participant();
            $participant->report_id = $report->id;
            $participant->person_id = $person_id;
            $participant->save();
        }

        return response()->json($report);
    }

    public function updateReport(Request $request, $id) {
        $this->validate($request, [
            'title' => 'required|string',
            'report' => 'required|string',
            'humor' => 'required|string',
        ]);

        $data = $request->all();

        $report = Report::find($id);
        $report->title = $data['title'];
        $report->report = $data['report'];
        $report->humor = $data['humor'];
        $report->save();

        return response()->json($report);
    }

    public function delete($id) {
        $report = Report::find($id);
        $report->delete();
        return response()->json('Report deleted successfully');
    }
}
