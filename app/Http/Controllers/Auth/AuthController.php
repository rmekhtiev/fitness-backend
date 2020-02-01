<?php

namespace App\Http\Controllers\Auth;

use App\Transformers\BaseTransformer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Validation\Rule;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Specialtactics\L5Api\Http\Controllers\Features\JWTAuthenticationTrait;

class AuthController extends Controller
{
    use JWTAuthenticationTrait;

    public function login(Request $request)
    {
        $validated = $this->validate($request, [
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        if (! $token = auth()->attempt(['email' => $validated['email'], 'password' => $validated['password']])) {
            throw new \Specialtactics\L5Api\Exceptions\UnauthorizedHttpException('Unauthorized login');
        }

        return $this->respondWithToken($token);
    }

    public function getUser()
    {
        return $this->response->item(
            $this->auth->user()->loadMissing(User::getItemWith()),
            User::$transformer ?? BaseTransformer::class
        );
    }
}
