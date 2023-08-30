<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
use App\Models\VerifyTokens;
use App\Mail\SendMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;


class PasswordResetRequestController extends Controller
{
    public function sendPasswordResetEmail(Request $request)
    {
        // If email does not exist
        if (!$this->validEmail($request->email)) {
            return response()->json([
                'response' => [
                    'message' => 'Email does not exist.',
                    'status' => 510,
                ],
            ], Response::HTTP_NOT_FOUND);
        } else {

            try {
                // If email exists
                $token = $this->sendMail($request->email);
                return response()->json([
                    'response' => [
                        'message' => 'Check your inbox, we have sent a link to reset email.',
                        'token' => $token,
                        'status' => 200,
                    ],
                ], Response::HTTP_OK);

            } catch (Exception $e) {
                return response()->json([
                    'response' => [
                        'message' => $e->getMessage(),
                        'status' => 400,
                    ],
                ]);
            } //try catch ends here
        }
    }

    public function sendMail($email)
    {
        $token = $this->generateToken($email);
        Mail::to($email)->send(new SendMail($token));
        return $token;
    }

    public function validEmail($email)
    {
        return !!User::where('email', $email)->first();
    }

    public function generateToken($email)
    {
        $isOtherToken = DB::table('verify_tokens')->where('email', $email)->first();

        if ($isOtherToken) {
            return $isOtherToken->token;
        }

        $token = Str::random(80);
        $this->storeToken($token, $email);
        return $token;
    }

    public function storeToken($token, $email)
    {
        $verify_token = new VerifyTokens;
        $verify_token->email = $email;
        $verify_token->token = $token;
        $verify_token->save();
    }
}
