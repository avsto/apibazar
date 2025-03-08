<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    //
    public function get(Request $request)
    {
        $query = Role::query();

        $desserts = $query->orderBy('id')->paginate($request->itemsPerPage ?? 10);

        return response()->json(['status' => '', 'message' => '', 'data' => $desserts]);
    }


    public function addupdate(Request $request)
    {
        if ($request->id) {
            $data = [];
            $data['name'] = $request->name;
            $data['slug'] = $request->slug;
            Role::where('id', $request->id)->update($data);
        } else {
            $data = [];
            $data['name'] = $request->name;
            $data['slug'] = $request->slug;
            Role::create($data);
        }

        return response()->json(['status' => '200', 'message' => 'Schemes added successfully',]);
    }
}
