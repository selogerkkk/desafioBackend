<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @OA\Post(
 *     path="/api/auth/login",
 *     summary="Autenticação de usuário",
 *     tags={"Autenticação"},
 *     security={{"bearer_token": {}}},
 *     @OA\Parameter(
 *         name="email",
 *         in="query",
 *         description="Endereço de e-mail do usuário",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *     @OA\Parameter(
 *         name="password",
 *         in="query",
 *         description="Senha do usuário",
 *         required=true,
 *         @OA\Schema(type="string")
 *     ),
 *    
 * @OA\Response(
 *     response="200",
 *     description="Token de autenticação",
 *     @OA\JsonContent(
 *         type="object",
 *         @OA\Property(property="access_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
 *         @OA\Property(property="token_type", type="string", example="bearer"),
 *         @OA\Property(property="expires_in", type="integer", example=3600),
 *     )
 * ),
 *     @OA\Response(
 *         response="401",
 *         description="Não autorizado",
 *         @OA\JsonContent(
 *             type="object",
 *             @OA\Property(property="message", type="string", example="Não autorizado.")
 *         )
 *     ),
 * )
 **/
class AuthController extends Controller
{
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);

        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Não autorizado'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth('api')->factory()->getTTL() * 60
        ]);
    }
}
