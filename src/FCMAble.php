<?php
/**
 * Created by IntelliJ IDEA.
 * User: subangkit
 * Date: 2019-11-13
 * Time: 14:35
 */

namespace BlackIT\FCMAble;


use BlackIT\FCMAble\Models\FCMToken;

use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

trait FCMAble
{
    public function fcm_tokens()
    {
        return $this->morphMany(FCMToken::class, 'fcmable');
    }

    public function fcm_tokens_application($application)
    {
        return $this->morphMany(FCMToken::class, 'fcmable')->where('application', $application);
    }

    public function fcm_token($application)
    {
        $agent = $this->getUserAgent();
        return $this->fcm_tokens_application($application)->where('agent',$agent)->first();
    }

    public function getUserAgent() {
        return request()->header('User-Agent');
    }

    public function registerFCMToken($application, $token) {
        $check = $this->fcm_tokens_application($application);
        if ($check)
            return $check;

        $token = new FCMToken();
        $token->token = $token;
        $token->application = $application;
        $token->agent = $this->getUserAgent();

        return $this->fcm_tokens()->save($token);
    }

    public function getFCMToken($application) {
        return $this->fcm_token($application);
    }

    public function sendPushNotification($application, $title, $body, $data, $time=1200, $sound='default') {
        $optionBuilder = new OptionsBuilder();
        $optionBuilder->setTimeToLive($time);

        $notificationBuilder = new PayloadNotificationBuilder($title);
        $notificationBuilder->setBody($body)->setSound($sound);

        $dataBuilder = new PayloadDataBuilder();
        $dataBuilder->addData($data);

        $option = $optionBuilder->build();
        $notification = $notificationBuilder->build();
        $data = $dataBuilder->build();

        $tokens = $this->fcm_tokens_application($application)->pluck('token')->toArray();

        $downstreamResponse = FCM::sendTo($tokens, $option, $notification, $data);
    }
}
