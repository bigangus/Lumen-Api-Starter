<?php

namespace App\Utils;

use TencentCloud\Common\Credential;
use TencentCloud\Common\Profile\ClientProfile;
use TencentCloud\Common\Profile\HttpProfile;
use TencentCloud\Sms\V20210111\Models\SendSmsRequest;
use TencentCloud\Sms\V20210111\SmsClient;

class TencentApi
{
    protected string $endpoint;
    protected string $secretId;
    protected string $secretKey;

    public function __construct(string $endpoint)
    {
        $this->endpoint = $endpoint;
        $this->secretId = config('tencent.secret_id');
        $this->secretKey = config('tencent.secret_key');
    }

    public function sendSms(array $params): void
    {
        $cred = new Credential($this->secretId, $this->secretKey);

        $httpProfile = new HttpProfile();
        $httpProfile->setEndpoint($this->endpoint);

        $clientProfile = new ClientProfile();
        $clientProfile->setHttpProfile($httpProfile);

        $client = new SmsClient($cred, '', $clientProfile);

        $req = new SendSmsRequest();

        $req->fromJsonString(json_encode($params));

        $resp = $client->SendSms($req);

        print_r($resp->toJsonString());
    }
}
