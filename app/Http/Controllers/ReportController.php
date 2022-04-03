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
            $q = request('q');
            $person = request('person');

            $query = Report::query()
                ->select('id', 'title', 'created_at')
                ->with('participants.person', function ($query) {
                    $query->select('id', 'name', 'nickname');
                })
                ->orderBy('created_at', 'desc');

            if ($q) {
                $query->where(function ($query) use ($q) {
                    $query->where('title', 'like', "%$q%")
                        ->orWhere('report', 'like', "%$q%");
                });
            }

            if ($person) {
                $query->whereHas('participants', function ($query) use ($person) {
                    $query->where('person_id', $person);
                });
            }

            $reports = $query->paginate(12, ['*'], 'current_page', $page);

            return response()->json(['success' => true, 'data' => $reports]);
        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
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
                    'person_id' => $participant['person_id'],
                    'type' => $participant['type'],
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
                    'person_id' => $participant['person_id'],
                    'type' => $participant['type'],
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
