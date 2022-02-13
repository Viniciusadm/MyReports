<?php

namespace App\Http\Controllers;

use App\Models\Person;
use App\Models\Report;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function get(): JsonResponse
    {
        try {
            $q = request('q');
            $page = request('current_page');
            $limit = request('limit');
            $column = request('column');
            $order = request('order');
            $fields = explode(',', request('fields'));

            $reports = Report::query()->select($fields)
                ->where('title', 'like', "%$q%")
                ->orderBy($column, $order)
                ->paginate($limit, ['*'], 'current_page', $page);

            return response()->json(['success' => true, 'data' => $reports]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function getById($id): JsonResponse
    {
        try {
            $report = Report::find($id);

            if (!$report) {
                return response()->json([
                    'message' => 'Report not found'
                ], 404);
            }

            $report->people = Person::whereIn('id', json_decode($report->persons_ids))->get();

            return response()->json($report);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getByPersonId($id): JsonResponse
    {
        try {
            $reports = Report::where('persons_ids', 'like', '%[' . $id . ']%')
            ->orWhere('persons_ids', 'like', '%,' . $id . ']%')
            ->orWhere('persons_ids', 'like', '%[' . $id . ',%')
            ->orWhere('persons_ids', 'like', '%,' . $id . ',%')
            ->get();

            foreach($reports as $report) {
                $report->people = Person::whereIn('id', json_decode($report->persons_ids))->get();
            }

            return response()->json($reports);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function create(Request $request): JsonResponse
    {
        try {
            $this->validate($request, [
                'title' => 'required',
                'report' => 'required',
                'humor' => 'required',
                'type' => 'required|in:personal,daily',
                'persons_ids' => 'array'
            ]);

            $data = $request->all();

            $report = new Report();

            $report->title = $data['title'];
            $report->report = $data['report'];
            $report->humor = $data['humor'];
            $report->type = $data['type'];
            $report->persons_ids = json_encode($data['persons_ids']);

            $report->save();

            return response()->json($report, 201);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function updateReport(Request $request, $id): JsonResponse
    {
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
                    'message' => 'Report not found'
                ], 404);
            }

            $report->title = $data['title'];
            $report->report = $data['report'];
            $report->humor = $data['humor'];
            $report->save();

            return response()->json($report);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $report = Report::find($id);

            if (!$report) {
                return response()->json([
                    'message' => 'Report not found'
                ], 404);
            }

            $report->delete();
            return response()->json([
                'message' => 'Report deleted'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
