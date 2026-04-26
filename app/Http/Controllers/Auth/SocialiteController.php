<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * Redirect the user to the provider authentication page.
     *
     * @param string $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }

        try {
            return Socialite::driver($provider)->redirect();
        } catch (\InvalidArgumentException $e) {
            // Error de configuración (ej: falta el client_id)
            return redirect()->route('login')->with('error', 'El servicio de ' . ucfirst($provider) . ' no está configurado correctamente en este momento.');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'No se pudo conectar con ' . ucfirst($provider) . '. Inténtalo de nuevo más tarde.');
        }
    }

    /**
     * Obtain the user information from the provider.
     *
     * @param string $provider
     * @return \Illuminate\Http\RedirectResponse
     */
    public function handleProviderCallback($provider)
    {
        if (!in_array($provider, ['google', 'facebook'])) {
            abort(404);
        }

        try {
            $socialUser = Socialite::driver($provider)->user();
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Hubo un problema al autenticar con ' . ucfirst($provider));
        }

        $user = User::where($provider . '_id', $socialUser->getId())
            ->orWhere('email', $socialUser->getEmail())
            ->first();

        if ($user) {
            // Update social ID and avatar if it's currently a social avatar or empty
            $userUpdateData = [];
            if (!$user->avatar || str_starts_with($user->avatar, 'http')) {
                $userUpdateData['avatar'] = $socialUser->getAvatar();
            }

            if (!$user->{$provider . '_id'}) {
                $userUpdateData[$provider . '_id'] = $socialUser->getId();
            }

            // Mark as verified if not already (since social providers verify emails)
            if (!$user->email_verified_at) {
                $userUpdateData['email_verified_at'] = now();
            }

            $user->update($userUpdateData);
            Auth::login($user);
        } else {
            // Create a new user
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(),
                'password' => Hash::make(Str::random(24)),
                $provider . '_id' => $socialUser->getId(),
                'avatar' => $socialUser->getAvatar(),
                'provider' => $provider,
                'email_verified_at' => now(), // Social providers verify emails
            ]);

            Auth::login($user);
        }


        return redirect()->intended('/dashboard');
    }
}
