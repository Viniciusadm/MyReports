<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getAll() {
        $reports = Report::all();
        return response()->json($reports);
    }

    public function getById($id) {
        $report = Report::find($id);
        return response()->json($report);
    }

    public function getByParticipantId($id) {
        $reports = DB::select("select reports.id, reports.report
        from reports, participants
        where participants.report_id = reports.id
        and participants.person_id = $id");

        return response()->json($reports);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'report' => 'required|string',
            'type' => 'required|in:personal,daily',
            'persons_ids' => 'required|array'
        ]);

        $data = $request->all();
        $report = new Report();

        $report->report = $data['report'];
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
            'report' => 'required|string'
        ]);

        $data = $request->all();

        $report = Report::find($id);
        $report->report = $data['report'];
        $report->save();

        return response()->json($report);
    }

    public function delete($id) {
        $report = Report::find($id);
        $report->delete();
        return response()->json('Report deleted successfully');
    }
}
