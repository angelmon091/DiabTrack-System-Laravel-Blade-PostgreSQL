<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Aviso de Seguridad</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-top: 4px solid #e74c3c;">
        <h2 style="color: #2c3e50; margin-top: 0;">Aviso de Seguridad</h2>
        
        <p style="color: #555; line-height: 1.6;">
            Hola, {{ $user->name }}. Se ha iniciado un proceso para cambiar tu dirección de correo electrónico en DiabTrack.
        </p>

        <p style="color: #555; line-height: 1.6;">
            La nueva dirección solicitada es: <strong>{{ $newEmail }}</strong>.
        </p>

        <div style="background-color: #fff3f3; border-left: 4px solid #e74c3c; padding: 15px; margin: 20px 0;">
            <p style="color: #c0392b; margin: 0; font-weight: bold;">
                ¿No fuiste tú?
            </p>
            <p style="color: #c0392b; margin: 5px 0 0 0; font-size: 0.9em;">
                Si tú no solicitaste este cambio, por favor contacta a nuestro equipo de soporte inmediatamente o cambia tu contraseña.
            </p>
        </div>

        <p style="color: #555; line-height: 1.6;">
            Si fuiste tú, simplemente ignora este mensaje. Hemos enviado las instrucciones de activación a tu <strong>nueva</strong> dirección de correo.
        </p>

        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #bdc3c7; font-size: 0.8em; text-align: center;">
            El equipo de DiabTrack
        </p>
    </div>
</body>
</html>
