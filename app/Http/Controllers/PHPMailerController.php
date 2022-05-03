<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class PHPMailerController extends Controller
{
    public function email()
    {
        return view("email");
    }

    /**
     * @OA\Post(
     * tags={"auth users"},
     * path="/api/subscriptions",
     * summary="Subscriptions",
     * description="Create subscription",
     * security = {{ "Bearer":{} }},
     * @OA\RequestBody(
     * required=true,
     * @OA\MediaType(
     * mediaType="application/json",
     * @OA\Schema(
     * @OA\Property(property="user_email", type="required,string,max:50,email,unique:subscriptions"),
     * example={
     * "user_email":"karina@mail.ru",
     * },
     * ),
     * ),
     * ),
     * @OA\Response(
     * response=200,
     * description="Success",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Вы подписаны на рассылку"),
     * ),
     * ),
     * @OA\Response(
     * response=401,
     * description="Unauthorized",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Unauthorized")
     * ),
     * ),
     * )
     */

    public function user_subscription(Request $request)
    {
        require base_path("vendor/autoload.php");
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        $validation = Validator::make($request->all(), [
            'user_email' => 'required|string|max:50|email|unique:subscriptions',
        ],
            [
                'user_email.max' => 'не более 50 символов',
                'user_email.email' => 'некоректный тип',
                'user_email.unique' => 'email уже используется',
                'user_email.required' => 'обязательно для заполнения',
            ]);

        if ($validation->fails()) {
            return response()->json([
                'user_email_error' => $validation->errors()->first('user_email')
            ], 400);
        }
        $subscription = new Subscription();
        $subscription->user_id = auth()->user()->id;
        $subscription->user_email = $request->input('user_email');
        $subscription->save();
        try {
            $from_name = 'karina';
            $body = 'Вы подписаны на рассылку';

            $mail->SMTPDebug = 0;
            $mail->isSMTP();
            $mail->Host = 'smtp.mail.ru';             //  smtp host
            $mail->SMTPAuth = true;
            $mail->Username = 'karina.andros@mail.ru';   //  sender username
            $mail->Password = '2711675k';       // sender password
            $mail->SMTPSecure = 'ssl';                  // encryption - ssl/tls
            $mail->Port = 465;                          // port - 587/465

            $mail->setFrom('karina.andros@mail.ru', $from_name);
            $mail->addAddress($request->input('user_email'));
            $mail->addReplyTo('karina.andros@mail.ru', 'karina');
            $mail->isHTML(true);
            $mail->Subject = 'Рассылка';
            $mail->Body = $body;
            $mail->addEmbeddedImage('img/cats.jpg', 'logo_2u');

            if (!$mail->send()) {
                return response()->json([
                    'message' => 'Ошибка'
                ]);
            } else {
                return response()->json([
                    'message' => 'Вы подписаны на рассылку'
                ]);
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Ошибка'
            ]);
        }


    }
}
