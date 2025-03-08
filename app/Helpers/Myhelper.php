<?php
use App\Models\Api;
use App\Models\ApiCommission;
use App\Models\Apilog;
use App\Models\Commission;
use App\Models\Scheme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

function sendOtp($phone, $message)
{

    $apiUrl = "http://web.mobiroute.in/domestic/sendsms/bulksms_v2.php";
    $params = [
        'apikey' => 'cXVpY2tmaW56MTIzOnJMbktINXdN',
        'type' => 'TEXT',
        'sender' => 'QUIKFZ',
        'entityId' => '1201166205959174959',
        'templateId' => '1207168063579057813',
        'mobile' => $phone,
        'message' => $message
    ];
    $response = Http::get($apiUrl, $params);

}


function callApi($url, $method, $parameter = [], $header = [], $txnid = '')
{
    $curl = curl_init();
    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 240,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => $method,
        CURLOPT_POSTFIELDS => $parameter,
        CURLOPT_HTTPHEADER => $header,
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    // insert login
    if ($txnid) {
        Apilog::create([
            'txnid' => $txnid,
            'url' => $url,
            'request' => json_encode($parameter),
            'header' => json_encode($header),
            'response' => json_encode($response),
        ]);
    }

    return $response;
}


function getCommission($amount, $scheme_id, $provider_id, $mem_type = 'value')
{
    $commission = 0;

    $scheme = Scheme::where('id', $scheme_id)->first();
    if ($scheme && $scheme->status == '1') {
        $comdata = Commission::select('type', $mem_type . ' as value')->whereHas('provider', function ($q) use ($provider_id) {
            $q->where('id', $provider_id);
        })->where('scheme_id', $scheme->id)->first();

        if ($comdata) {
            if ($comdata->type == 'percentage') {
                $commission = $amount * $comdata->value / 100;
            } else if ($comdata->type == 'flat') {
                $commission = $comdata->value;
            }
        }
    }

    return $commission;
}

function getApiCommission($amount, $api_id, $provider_id)
{
    $commission = 0;

    $api = Api::where('id', $api_id)->first();
    if ($api && $api->status == '1') {
        $comdata = ApiCommission::whereHas('provider', function ($q) use ($provider_id) {
            $q->where('id', $provider_id);
        })->where('api_id', $api->id)->first();

        if ($comdata) {
            if ($comdata->type == 'percentage') {
                $commission = $amount * $comdata->value / 100;
            } else if ($comdata->type == 'flat') {
                $commission = $comdata->value;
            }
        }
    }

    return $commission;
}