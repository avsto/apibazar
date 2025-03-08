<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Commission;
use App\Models\Provider;
use App\Models\Scheme;
use Illuminate\Http\Request;

class SchemesController extends Controller
{
    //
    public function get(Request $request)
    {
        $query = Scheme::query();

        $desserts = $query->orderBy('id')->paginate($request->itemsPerPage ?? 10);

        return response()->json(['status' => '', 'message' => '', 'data' => $desserts]);
    }


    public function addupdate(Request $request)
    {

        if ($request->id) {
            $data = [];
            $data['name'] = $request->name;
            Scheme::where('id', $request->id)->update($data);
        } else {
            $data = [];
            $data['name'] = $request->name;
            Scheme::create($data);
        }

        return response()->json(['status' => '200', 'message' => 'Schemes added successfully',]);
    }

    public function remove($id)
    {
        Scheme::where('id', $id)->delete();
        Commission::where('scheme_id', $id)->delete();
        return response()->json(['status' => '200', 'message' => 'Schemes deleted successfully',]);
    }


    public function schemecommission($option1, $option2)
    {
        $scheme = Scheme::findorfail($option1);

        $providers = Provider::where('type', $option2)->orderBy('name', 'ASC')->get();
        foreach ($providers as $key => $provider) {
            $comm = Commission::where('scheme_id', $scheme->id)->where('provider_id', $provider->id)->first();
            if ($comm) {
                $provider->com_type = $comm->type;
                $provider->com_value = $comm->value;
                $provider->com_parentvalue = $comm->parent_value;
            } else {
                $provider->com_type = 'flat';
                $provider->com_value = 0;
                $provider->com_parentvalue = 0;
            }
            $provider->scheme_id = $scheme->id;
        }

        return response()->json(['status' => 200, 'message' => 'success', 'data' => $providers]);
    }

    public function schemecommissionupdate(Request $request)
    {
        try {
            foreach ($request->all() as $scheme) {
                Commission::updateOrCreate(
                    [
                        'scheme_id' => $scheme['scheme_id'],
                        'provider_id' => $scheme['id'],
                    ],
                    [
                        'scheme_id' => $scheme['scheme_id'],
                        'provider_id' => $scheme['id'],
                        'type' => $scheme['com_type'],
                        'value' => $scheme['com_value'],
                        'parent_value' => $scheme['com_parentvalue'],
                    ]
                );
            }

            return response()->json(['status' => 200, 'message' => 'API commission has been updated...'], );
        } catch (\Exception $e) {
            return $e;
        }
    }

}
