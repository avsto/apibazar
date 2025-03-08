<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Api;
use App\Models\Provider;
use App\Models\Report;
use App\Models\User;
use App\Models\UserAepsDetails;
use Auth;
use Illuminate\Http\Request;

class AepsController extends Controller
{

    public function generateTxnId()
    {
        return 'QP' . rand(1111111111, 9999999999);
    }

    public function getHeader($crypttext, $body, $deviceIMEI = "")
    {
        return [
            'Content-Type: text/xml',
            'trnTimestamp:' . date('d/m/Y H:i:s'),
            'hash:' . base64_encode(hash('sha256', json_encode($body), true)),
            'eskey:' . base64_encode($crypttext),
            'deviceIMEI: ' . $deviceIMEI
        ];
    }

    public function encryptBody($body)
    {

        $key = '';
        $mt_rand = array(0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15);

        foreach ($mt_rand as $chr) {
            $key .= chr($chr);
        }

        $iv = '06f2f04cc530364f';
        $ciphertext_raw = openssl_encrypt(json_encode($body), 'AES-128-CBC', $key, $options = OPENSSL_RAW_DATA, $iv);
        $parameter = base64_encode($ciphertext_raw);

        $fp = fopen(public_path('key') . '/fingpaypublickey.txt', 'r');
        $pub_key_string = fread($fp, 8192);
        fclose($fp);

        openssl_public_encrypt($key, $crypttext, $pub_key_string);
        return [$crypttext, $parameter];
    }

    //
    public function onboarding(Request $request)
    {

        $provider = Provider::where(['option1' => 'onboarding', 'type' => 'aeps'])->first();

        if ($provider->status != 1) {
            return response()->json(['status' => 201, 'message' => 'AEPS onboarding has been stoped']);
        }


        $user = User::where('id', Auth::id())->first();

        if ($user->aadhar_verified == 0) {
            return response()->json(['status' => 201, 'message' => 'Aadhaar Verification pending']);
        }

        $api = Api::where('id', $provider->api_id)->first();

        $apiRequestData = [];

        switch ($api->name) {

            case 'fingpay':

                $body = [
                    'username' => $api->username,
                    'password' => md5($api->password),
                    'supermerchantId' => $api->option1,
                    // 'ipAddress' => $post->ip(),
                    'latitude' => $user->aepsDetails->latitude,
                    'longitude' => $user->aepsDetails->longitude,
                    'merchants' => [
                        array(
                            'merchantLoginId' => $user->aepsDetails->merchantLoginId,
                            'merchantpassword' => $user->aepsDetails->merchantpassword,
                            'merchantLoginPin' => $user->aepsDetails->merchantLoginPin,
                            'merchantName' => $user->aepsDetails->merchantName,
                            'merchantPhoneNumber' => $user->aepsDetails->merchantPhoneNumber,
                            'emailId' => $user->aepsDetails->emailId,
                            'merchantPinCode' => $user->aepsDetails->merchantPinCode,
                            'cancellationCheckImages' => null,
                            'shopAndPanImage' => "",
                            'ekycDocuments' => "",
                            'merchantCityName' => $user->aepsDetails->merchantCityName,
                            'merchantAddress' => array(
                                'merchantAddress' => $user->aepsDetails->merchantAddress,
                                'merchantState' => $user->aepsDetails->merchantState,
                            ),
                            'kyc' => array(
                                'userPan' => $user->aepsDetails->userPan,
                                'aadhaarNumber' => $user->aepsDetails->aadharNumber,
                                'gstInNumber' => '',
                            ),
                            'settlement' => array(
                                'companyBankAccountNumber' => "",
                                'bankIfscCode' => "",
                                'companyBankName' => "",
                                'bankBranchName' => "",
                                'bankAccountName' => "",
                            )
                        )
                    ]
                ];


                $apiRequestData['url'] = "{$api->url}/fpaepsweb/api/onboarding/merchant/creation/php/m1";

                [$crypttext, $parameter] = $this->encryptBody($body);

                $apiRequestData['header'] = $this->getHeader($crypttext, $body);

                $apiRequestData['parameter'] = $parameter;

                break;

            case 'paysprint':

                break;

            default:
                return response()->json(['status' => 201, 'message' => 'AEPS onboarding has been stopesdf']);
        }

        $txnid = rand(1111111111, 9999999999);
        $profit = getCommission(200, $user->scheme_id, $provider->id);
        $apiprofit = getApiCommission(200, $provider->api_id, $provider->id);

        $reportdoc = [
            'number' => '1234567890',
            'mobile' => $user->phone,
            'provider_id' => $provider->id,
            'api_id' => $provider->api_id,
            'amount' => 0,
            'profit' => $profit,
            'apitxnid' => $txnid,
            'txnid' => $txnid,
            'status' => 'pending',
            'user_id' => $user->id,
            'credit_by' => $user->id,
            'rtype' => 'main',
            'via' => 'app',
            'balance' => $user->wallet,
            'trans_type' => 'debit',
            'product' => $provider->option1,
            'service' => $provider->type,
            'wallet_type' => 'mainwallet',
            'api_profit' => $apiprofit,
            'adminprofit' => ($apiprofit - $profit),
        ];

        if ($api->name == 'fingpay') {

            $report = Report::create($reportdoc);

            $result = callApi($apiRequestData['url'], 'POST', $apiRequestData['header'], $apiRequestData['header'], $txnid);
            $respn = json_decode($result);
            if ($respn->status == false) {
                return response()->json(['status' => 201, 'message' => $respn->message]);
            } else {
                return response()->json(['status' => 200, 'message' => 'success', 'data' => $respn->data]);
            }
        }

    }
}
