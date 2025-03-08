<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Provider;
use Illuminate\Http\Request;

class ProviderController extends Controller
{
    //

    public function get(Request $request)
    {

        $query = Provider::query();

        $desserts = $query->orderBy('id')->paginate($request->itemsPerPage ?? 10);

        return response()->json(['status' => '', 'message' => '', 'data' => $desserts]);

    }

    public function add_and_update(Request $request)
    {
        if ($request->input('id')) {
            $update = Provider::where('id', $request->input('id'))->update($request->all());
            if ($update) {
                return response()->json(['stauts' => 200, 'message' => 'Provider successfully update']);
            } else {
                return response()->json(['stauts' => 201, 'message' => 'something went wrong']);
            }
        } else {

            $data = [];
            $data['api_id'] = $request->api_id;
            $data['image'] = $request->image;
            $data['name'] = $request->name;
            $data['option1'] = $request->option1;
            $data['option2'] = $request->option2;
            $data['option3'] = $request->option3;
            $data['option4'] = $request->option4;
            $data['option5'] = $request->option5;
            $data['option6'] = $request->option6;
            $data['type'] = $request->type;

            $repos = Provider::create($data);

            if ($repos) {
                return response()->json(['stauts' => 200, 'message' => 'Provider successfully added']);
            } else {
                return response()->json(['stauts' => 201, 'message' => 'something went wrong']);
            }

        }
    }
}
