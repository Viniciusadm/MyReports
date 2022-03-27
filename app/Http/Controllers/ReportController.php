<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Report;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $page = request('page');

            $reports = Report::query()
                ->with('participants.person')
                ->orderBy('created_at', 'desc')
                ->paginate(12, ['*'], 'current_page', $page);

            return response()->json(['success' => true, 'data' => $reports]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $report = Report::query()
                ->with('participants.person')
                ->find($id);

            if (!$report) {
                return response()->json(['message' => 'Relato não encontrado.'], 404);
            }

            return response()->json(['success' => true, 'data' => $report]);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function getByPersonId($id): JsonResponse
    {
        try {
            $reports = Report::query()
                ->with('participants')
                ->whereHas('participants', function ($query) use ($id) {
                    $query->where('person_id', $id);
                })
                ->get();

            return response()->json(['success' => true, 'data' => $reports]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request): JsonResponse
    {
        try {
            $data = $request->all();

            $report = Report::query()->create([
                'title' => $data['title'],
                'report' => $data['report'],
                'humor' => $data['humor'],
                'type' => $data['type'],
            ]);

            $participants = $data['participants'];

            foreach ($participants as $participant) {
                Participant::query()->create([
                    'report_id' => $report['id'],
                    'person_id' => $participant,
                ]);
            }

            return response()->json(['success' => true, 'data' => $report]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id): JsonResponse
    {
        try {
            $data = $request->all();

            $report = Report::query()->find($id);

            if (!$report) {
                return response()->json(['message' => 'Relato não encontrado.'], 404);
            }

            $report->update([
                'title' => $data['title'],
                'report' => $data['report'],
                'humor' => $data['humor'],
                'type' => $data['type'],
            ]);

            $participants = $data['participants'];

            Participant::query()->where('report_id', $id)->delete();

            foreach ($participants as $participant) {
                Participant::query()->create([
                    'report_id' => $report['id'],
                    'person_id' => $participant,
                ]);
            }

            return response()->json(['success' => true, 'data' => $report]);
        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function delete($id): JsonResponse
    {
        try {
            $report = Report::query()->find($id);

            if (!$report) {
                return response()->json(['message' => 'Relato não encontrado.'], 404);
            }

            $report->delete();

            return response()->json(['message' => 'Relato deletado com sucesso!']);

        } catch (Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }
}
