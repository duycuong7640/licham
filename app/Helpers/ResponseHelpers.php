<?php

namespace App\Helpers;

class ResponseHelpers
{

    public static function reponseUnauthorized($message = null)
    {
        return response()->json(['error' => !empty($message) ? $message : __('messages.response.unauthenticated_error')], 401);
    }

    public static function reponseValidator($message = null)
    {
        return response()->json(['error' => !empty($message) ? $message : __('messages.response.unprocessable_entity')], 422);
    }

    public static function reponseValidatorData($data = [], $message = null)
    {
        return response()->json(['response' => $data, 'error' => !empty($message) ? $message : __('messages.response.unprocessable_entity')], 422);
    }

    public static function responseSuccess($data = [])
    {
        return response()->json($data, 200);
    }

    public static function responseServerError($message = null)
    {
        return response()->json(['error' => !empty($message) ? $message : __('messages.response.internal_server_error')], 500);
    }

    public static function responseNotFound($message = null)
    {
        return response()->json(['error' => !empty($message) ? $message : __('messages.response.resource_not_found')], 404);
    }

    public static function responsePermissionError($message = null)
    {
        return response()->json(['error' => !empty($message) ? $message : __('messages.response.execute_access_forbidden')], 403);
    }

//    public static function messageSlack($_data){
//        Notification::route('slack', env('SLACK_WEBHOOK'))
//            ->notify(new SlackNotification(@json_encode($_data)));
//    }
//
//
//    public static function messageSlackEmergency($_data){
//        Notification::route('slack', env('SLACK_WEBHOOK_EMERGENCY'))
//            ->notify(new SlackNotification(@json_encode($_data)));
//    }
}
