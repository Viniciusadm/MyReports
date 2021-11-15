<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ParticipantController extends Controller
{
    public function getAll() {
        $participants = Participant::all();
        return response()->json($participants);
    }

    public function getById($id) {
        $participant = Participant::find($id);
        return response()->json($participant);
    }

    public function getByIdReport($id) {
        $participants = DB::select("select people.id, people.name
        from people
        inner join participants on (participants.person_id = people.id)
        inner join reports on (participants.report_id = reports.id)
        where reports.id = $id");

        return response()->json($participants);
    }

    public function delete($id) {
        $participant = Participant::find($id);
        $participant->delete();
        return response()->json('Participant deleted successfully');
    }

    public function createByIdReport(Request $request, $id) {
        $this->validate($request, [
            'person_id' => 'required|integer',
        ]);

        $data = $request->all();

        $participant = new Participant();
        $participant->report_id = $id;
        $participant->person_id = $data['person_id'];
        $participant->save();

        return response()->json($participant);
    }
}
