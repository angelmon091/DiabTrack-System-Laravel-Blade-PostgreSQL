<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\EmailChangeRequest;
use App\Mail\VerifyEmailChange;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

/**
 * Clase ProfileController
 * 
 * Gestiona la visualización y edición del perfil del usuario.
 * Permite actualizar la información personal y eliminar la cuenta.
 */
class ProfileController extends Controller
{
    /**
     * Muestra el formulario de edición del perfil.
     * 
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\View\View
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Actualiza la información del perfil del usuario.
     *
     * @param \App\Http\Requests\ProfileUpdateRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $oldEmail = $user->email;
        $newEmail = $request->validated()['email'];
        $status = 'profile-updated';

        $user->fill($request->except('email'));

        if ($oldEmail !== $newEmail) {
            // Eliminar solicitudes previas del mismo usuario
            EmailChangeRequest::where('user_id', $user->id)->delete();

            $token = Str::random(64);
            EmailChangeRequest::create([
                'user_id' => $user->id,
                'new_email' => $newEmail,
                'token' => $token,
                'expires_at' => now()->addHour(),
            ]);

            Mail::to($oldEmail)->send(new VerifyEmailChange($user, $token, $newEmail));
            $status = 'email-change-requested';
        }

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = 'avatars/' . $user->id . '_' . time() . '.webp';
            
            if ($user->avatar && !str_starts_with($user->avatar, 'http')) {
                Storage::disk('public')->delete($user->avatar);
            }

            $manager = new ImageManager(new Driver());
            $image = $manager->read($file);
            
            $encoded = $image->cover(150, 150)->toWebp(80);
            
            Storage::disk('public')->put($filename, $encoded->toString());
            
            $user->avatar = $filename;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', $status);
    }

    /**
     * Verifica y aplica el cambio de correo electrónico.
     */
    public function verifyEmail(Request $request, $token): RedirectResponse
    {
        $changeRequest = EmailChangeRequest::where('token', $token)
            ->where('expires_at', '>', now())
            ->first();

        if (!$changeRequest) {
            return Redirect::route('profile.edit')->with('error', 'El enlace de verificación ha expirado o es inválido.');
        }

        $user = $changeRequest->user;
        $user->email = $changeRequest->new_email;
        $user->email_verified_at = now();
        $user->save();

        $changeRequest->delete();

        return Redirect::route('profile.edit')->with('status', 'email-updated');
    }

    /**
     * Elimina la cuenta del usuario.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
