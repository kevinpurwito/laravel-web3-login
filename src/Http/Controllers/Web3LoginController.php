<?php

namespace Kevinpurwito\Web3Login\Http\Controllers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Kevinpurwito\Web3Login\Facades\Signature;
use Kevinpurwito\Web3Login\Web3Login;

class Web3LoginController
{
    protected function getUserModel(): Model
    {
        return app(config('auth.providers.users.model'));
    }

    protected function getWalletColumn(): string
    {
        return strval(config('web3.wallet_address_column', 'wallet'));
    }

    public function signature(Request $request)
    {
        $request->session()->put('nonce', $nonce = Str::random());

        return Signature::generate($nonce);
    }

    public function link(Request $request): JsonResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'regex:/0x[a-fA-F0-9]{40}/m'],
            'signature' => ['required', 'string', 'regex:/^0x([A-Fa-f0-9]{130})$/'],
        ]);

        if (! Signature::verify($request->session()->pull('nonce'), $request->input('signature'), $request->input('address'))) {
            // throw ValidationException::withMessages(['signature' => 'Signature verification failed.']);
            return response()->json(['success' => false, 'message' => 'Signature verification failed.'], 422);
        }

        $request->user()->update([
            $this->getWalletColumn() => strtolower($request->input('address')),
        ]);

        // return new Response('', 204);
        return response()->json(['success' => true, 'message' => 'User linked.'], 204);
    }

    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'regex:/0x[a-fA-F0-9]{40}/m'],
            'signature' => ['required', 'string', 'regex:/^0x([A-Fa-f0-9]{130})$/'],
        ]);

        if (! Signature::verify($request->session()->pull('nonce'), $request->input('signature'), $request->input('address'))) {
            // throw ValidationException::withMessages(['signature' => 'Signature verification failed.']);
            return response()->json(['success' => false, 'message' => 'Signature verification failed.'], 422);
        }

        if (Web3Login::$retrieveUserCallback) {
            $user = call_user_func(Web3Login::$retrieveUserCallback, strtolower($request->input('address')));
        } else {
            // $user = $this->getUserModel()->where($this->getWalletColumn(), strtolower($request->input('address')))->first();

            $user = $this->getUserModel()->firstOrCreate([
                $this->getWalletColumn() => strtolower($request->input('address')),
            ], [
                'name' => strtolower($request->input('address')),
            ]);
        }

        if (! $user) {
            // throw ValidationException::withMessages(['address' => 'Address not registered.']);
            return response()->json(['success' => false, 'message' => 'User not found.'], 404);
        }

        Auth::login($user);

        // return new Response('', 204);
        return response()->json(['success' => true, 'message' => 'User logged in.'], 204);
    }
}
