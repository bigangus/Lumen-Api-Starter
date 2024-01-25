<?php
namespace App\Utils;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\SmsClient;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;

class TencentSms
{
    public function sendSms(array $params): void
    {
        $cred = new Credential(config('tencent.secret_id'), config('tencent.secret_key'));

        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint(config('tencent.endpoint'));

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        $client = new SmsClient($cred, '', $clientProfile);

        $req = new SendSmsRequest();

        $req->fromJsonString(json_encode($params));

        $resp = $client->SendSms($req);

        print_r($resp->toJsonString());
    }
}
