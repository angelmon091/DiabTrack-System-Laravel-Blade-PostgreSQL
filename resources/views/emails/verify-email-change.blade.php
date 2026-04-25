<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Verificación de Cambio de Correo</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #2c3e50; margin-top: 0;">Hola, {{ $user->name }}</h2>
        
        <p style="color: #555; line-height: 1.6;">
            Hemos recibido una solicitud para cambiar el correo electrónico asociado a tu cuenta de DiabTrack a <strong>{{ $newEmail }}</strong>.
        </p>

        <p style="color: #555; line-height: 1.6;">
            Por tu seguridad, el correo no se cambiará hasta que verifiques esta acción haciendo clic en el siguiente botón:
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ route('profile.email.verify', ['token' => $token]) }}" style="background-color: #00C2E0; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">
                Verificar y Cambiar Correo
            </a>
        </div>

        <p style="color: #7f8c8d; font-size: 0.9em; margin-bottom: 0;">
            Si tú no hiciste esta solicitud, puedes ignorar este correo de forma segura. Tu correo actual seguirá activo y la solicitud expirará en 60 minutos.
        </p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #bdc3c7; font-size: 0.8em; text-align: center;">
            El equipo de DiabTrack
        </p>
    </div>
</body>
</html>
