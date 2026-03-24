<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 30px;">

    <h2 style="color: #2d6a4f;">Confirmación de Cita Médica</h2>
    <p>Hola <strong>{{ $appointment->patient->user->name }}</strong>, tu cita ha sido confirmada.</p>

    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
        <tr style="background:#f0f0f0;">
            <td style="padding:8px; border:1px solid #ddd;"><strong>Doctor</strong></td>
            <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->doctor->user->name }}</td>
        </tr>
        <tr>
            <td style="padding:8px; border:1px solid #ddd;"><strong>Fecha</strong></td>
            <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->date }}</td>
        </tr>
        <tr style="background:#f0f0f0;">
            <td style="padding:8px; border:1px solid #ddd;"><strong>Hora</strong></td>
            <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->time }}</td>
        </tr>
    </table>

    <p style="margin-top:20px;">Se adjunta tu comprobante en PDF.</p>
    <p style="color:#888; font-size:12px;">— {{ config('app.name') }}</p>

</body>
</html>