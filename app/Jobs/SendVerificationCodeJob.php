<?php

namespace App\Jobs;

use App\Utils\TencentApi;

class SendVerificationCodeJob extends Job
{
    protected string $phone;
    protected int $code;

    public function __construct(string $phone, int $code)
    {
        $this->phone = $phone;
        $this->code = $code;
    }

    public function handle(): void
    {
        if (config('settings.default_sms_client') == 'tencent') {
            (new TencentApi())->sendSms([
                'PhoneNumberSet' => [
                    "+86{$this->phone}"
                ],
                'TemplateID' => config('tencent.sms.template_id'),
                'Sign' => config('tencent.sms.sign'),
                'TemplateParamSet' => [
                    $this->code
                ],
                'SmsSdkAppid' => config('tencent.sms.app_id')
            ]);
        }
    }
}
