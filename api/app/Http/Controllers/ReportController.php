<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function getAll() {
        try {
            $reports = Report::get();

            foreach($reports as $report) {
                $report->person = Person::whereIn('id', json_decode($report->persons_ids))->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $reports
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getById($id) {
        try {
            $report = Report::find($id);
            
            $report->persons = Person::whereIn('id', json_decode($report->persons_ids))->get();

            if (!$report) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Report not found'
                ], 404);
            }
    
            return response()->json($report);    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByPersonId($id) {
        try {
            $reports = Report::where('persons_ids', 'like', '%[' . $id . ']%')
            ->orWhere('persons_ids', 'like', '%,' . $id . ']%')
            ->orWhere('persons_ids', 'like', '%[' . $id . ',%')
            ->orWhere('persons_ids', 'like', '%,' . $id . ',%')
            ->get();

            foreach($reports as $report) {
                $report->person = Person::whereIn('id', json_decode($report->persons_ids))->get();
            }

            return response()->json([
                'status' => 'success',
                'data' => $reports
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request) {
        try {
            $this->validate($request, [
                'title' => 'required',
                'report' => 'required',
                'humor' => 'required',
                'type' => 'required|in:personal,daily',
                'persons_ids' => 'required|array'
            ]);
    
            $data = $request->all();
            $report = new Report();
            
            $report->title = $data['title'];
            $report->report = $data['report'];
            $report->humor = $data['humor'];
            $report->type = $data['type'];
            $report->persons_ids = json_encode($data['persons_ids']);

            $report->save();
    
            return response()->json([
                'status' => 'success',
                'report' => $report
            ], 201);
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function updateReport(Request $request, $id) {
        try {
            $this->validate($request, [
                'title' => 'required',
                'report' => 'required',
                'humor' => 'required',
            ]);
    
            $data = $request->all();
    
            $report = Report::find($id);

            if (!$report) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Report not found'
                ], 404);
            }

            $report->title = $data['title'];
            $report->report = $data['report'];
            $report->humor = $data['humor'];
            $report->save();
    
            return response()->json($report);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id) {
        try {
            $report = Report::find($id);

            if (!$report) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Report not found'
                ], 404);
            }

            $report->delete();
            return response()->json('Report deleted successfully');    
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
