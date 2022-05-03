<?php

namespace App\Http\Controllers\Users;

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class EmailController extends Controller
{
    public function email_verify(){
        return view('auth.verify');
    }

    /**
     *     @OA\Post(
     *     tags={"verify email"},
     *     path="/api/email/verification-notification",
     *     summary="Repeate email",
     *     description="Send repeate email",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Письмо отправлено повторно"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Unauthorized")
     *     ),
     *     ),
     *     )
     */
    public function repeated_email(Request $request){
        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Письмо отправлено повторно']);
    }

    /**
     *     @OA\Get(
     *     tags={"verify email"},
     *     path="/api/email/verify/{id}/{hash}",
     *     summary="Verify email",
     *     description="Verify email",
     *     security = {{ "Bearer":{} }},
     *     @OA\Response(
     *     response=200,
     *     description="Success",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Вы успешно подтвердили свою почту"),
     *     ),
     *     ),
     *     @OA\Response(
     *     response=401,
     *     description="Unauthorized",
     *     @OA\JsonContent(
     *     @OA\Property(property="message", type="string", example="Unauthorized")
     *     ),
     *     ),
     *     )
     */
    public function verify_email(EmailVerificationRequest $request){
        $request->fulfill();
        return response()->json('Вы успешно подтвердили свою почту');
    }
}
