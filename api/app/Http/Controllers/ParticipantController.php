<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;

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
        $participants = Participant::select('people.id', 'people.name')
        ->join('people', 'people.id', '=', 'participants.person_id')
        ->where('report_id', $id)
        ->get();

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
