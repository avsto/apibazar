<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\ApiCommission;
use App\Models\Provider;
use Illuminate\Http\Request;
use Validator;

class ApiController extends Controller
{
    //

    public function get(Request $request)
    {
        $query = Api::query();

        $desserts = $query->orderBy('id')->paginate($request->itemsPerPage ?? 10);

        return response()->json(['status' => '', 'message' => '', 'data' => $desserts]);
    }

    public function getAll()
    {
        $query = Api::get();
        return response()->json(['status' => '', 'message' => '', 'data' => $query]);
    }

    public function add_and_update(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'product' => 'required',
            'name' => 'required',
            'url' => 'required',
            'code' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['status' => 201, 'message' => $validator->errors()]);
        }


        if ($request->input('id')) {
            $update = Api::where('id', $request->input('id'))->update($request->all());
            if ($update) {
                return response()->json(['stauts' => 200, 'message' => 'Api successfully update']);
            } else {
                return response()->json(['stauts' => 201, 'message' => 'something went wrong']);
            }
        } else {

            $data = [];
            $data['product'] = $request->product;
            $data['name'] = $request->name;
            $data['url'] = $request->url;
            $data['username'] = $request->username;
            $data['password'] = $request->password;
            $data['option1'] = $request->option1;
            $data['option2'] = $request->option2;
            $data['code'] = $request->code;

            $repos = Api::create($data);

            if ($repos) {
                return response()->json(['stauts' => 200, 'message' => 'Api successfully added']);
            } else {
                return response()->json(['stauts' => 201, 'message' => 'something went wrong']);
            }

        }
    }



    public function apicommission($option1 = null, $option2 = null)
    {
        $api = Api::findorfail($option1);
        $providers = Provider::where('type', $option2)->orderBy('name', 'ASC')->get();
        foreach ($providers as $key => $provider) {
            $comm = ApiCommission::where('api_id', $api->id)->where('provider_id', $provider->id)->first();
            if ($comm) {
                $provider->com_type = $comm->type;
                $provider->com_value = $comm->value;
            } else {
                $provider->com_type = 'flat';
                $provider->com_value = 0;
            }
        }
        return response()->json(['status' => 200, 'message' => 'success', 'data' => $providers]);
    }

    public function apicommissionupdate(Request $request)
    {
        try {
            foreach ($request->all() as $api) {
                ApiCommission::updateOrCreate(
                    [
                        'api_id' => $api['api_id'],
                        'provider_id' => $api['id'],
                    ],
                    [
                        'api_id' => $api['api_id'],
                        'provider_id' => $api['id'],
                        'type' => $api['com_type'],
                        'value' => $api['com_value'],
                    ]
                );
            }

            return response()->json(['status' => 200, 'message' => 'API commission has been updated...'], );
        } catch (\Exception $e) {
            return $e;
        }

    }
}
