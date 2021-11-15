<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;

class PersonController extends Controller
{
    public function getAll() {
        $people = Person::all();
        return response()->json($people);
    }

    public function getById($id) {
        $person = Person::find($id);
        return response()->json($person);
    }

    public function create(Request $request) {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'email',
            'phone' => 'numeric',
            'birthday' => 'date'
        ]);

        $data = $request->all();

        $person = new Person();
        $person->name = $data['name'];

        if (isset($data['birthday'])) {
            $person->birthday = $data['birthday'];
        }

        if (isset($data['email'])) {
            $person->email = $data['email'];
        }

        if (isset($data['phone'])) {
            $person->phone = $data['phone'];
        }

        if (isset($data['address'])) {
            $person->address = $data['address'];
        }

        if (isset($data['description'])) {
            $person->description = $data['description'];
        }

        $person->save();

        return response()->json($person);
    }

    public function delete($id) {
        $person = Person::find($id);
        $person->delete();

        return response()->json('Person deleted successfully');
    }

    public function update(Request $request, $id) {
        $this->validate($request, [
            'email' => 'email',
            'phone' => 'numeric',
            'birthday' => 'date'
        ]);

        $data = $request->all();

        $person = Person::find($id);

        if (isset($data['name'])) {
            $person->name = $data['name'];
        }

        if (isset($data['birthday'])) {
            $person->birthday = $data['birthday'];
        }

        if (isset($data['email'])) {
            $person->email = $data['email'];
        }

        if (isset($data['phone'])) {
            $person->phone = $data['phone'];
        }

        if (isset($data['address'])) {
            $person->address = $data['address'];
        }

        if (isset($data['description'])) {
            $person->description = $data['description'];
        }

        $person->save();

        return response()->json($person);
    }
}
