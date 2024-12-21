<?php

namespace App\Http\Controllers;

use App\Models\OtpCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\User;

class OtpController extends Controller
{
    public function verifyOtp(Request $request)
    {
        $user = User::where('username', $request->input('username'))->first();

        $curent_code = OtpCode::where('user_id', $user->id)->latest()->first();

        $exp = $curent_code->expires_at > Carbon::now();

        if ($request->input('otp') == $curent_code->code and $exp) {

            $userTokenCount = $user->tokens()->where('revoked', 0)->count();
            $toDelete = $userTokenCount - env('MAX_ACTIVE_TOKENS', 3);

            if ($toDelete > 0) {
                $oldestTokens = $user->tokens()->oldest()->get();
                for ($i = 0; $i <= $toDelete; $i++) {
                    $oldestTokens[$i]->revoke();
                }
            }

            if (env('MAX_ACTIVE_TOKENS') == 0) {
                return response()->json(['message' => 'Админ множко дурачек и поставил в env max_active_tokens равный нулю']);
            }
            
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            $token->expires_at = Carbon::now()->addDays(env('TOKEN_EXPIRATION_DAYS', 15));
            $token->save();

            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse($tokenResult->token->expires_at)->toDateTimeString(),
            ]);
        } else {
            return response()->json(['message' => 'Все не заебись']);
        }
    }
}
