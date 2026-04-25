<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Restablecer Contraseña</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
        <h2 style="color: #2c3e50; margin-top: 0;">Hola, {{ $name }}</h2>
        
        <p style="color: #555; line-height: 1.6;">
            Estás recibiendo este correo porque recibimos una solicitud de restablecimiento de contraseña para tu cuenta en DiabTrack.
        </p>

        <p style="color: #555; line-height: 1.6;">
            Haz clic en el botón de abajo para restablecer tu contraseña. Este enlace expirará en 60 minutos.
        </p>

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $url }}" style="background-color: #00C2E0; color: #ffffff; text-decoration: none; padding: 12px 25px; border-radius: 5px; font-weight: bold; display: inline-block;">
                Restablecer Contraseña
            </a>
        </div>

        <p style="color: #7f8c8d; font-size: 0.9em; margin-bottom: 0;">
            Si no solicitaste un restablecimiento de contraseña, no es necesario realizar ninguna otra acción.
        </p>
        
        <hr style="border: 0; border-top: 1px solid #eee; margin: 20px 0;">
        
        <p style="color: #bdc3c7; font-size: 0.8em; text-align: center;">
            El equipo de DiabTrack
        </p>
    </div>
</body>
</html>
