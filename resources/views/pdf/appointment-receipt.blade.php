<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 40px;">

    <h1 style="color: #2d6a4f; border-bottom: 2px solid #2d6a4f; padding-bottom: 10px;">
        Comprobante de Cita
    </h1>

    <p><strong>Paciente:</strong> {{ $appointment->patient->user->name }}</p>
    <p><strong>Doctor:</strong> {{ $appointment->doctor->user->name }}</p>
    <p><strong>Especialidad:</strong> {{ $appointment->doctor->speciality->name ?? 'N/A' }}</p>
    <p><strong>Fecha:</strong> {{ $appointment->date }}</p>
    <p><strong>Hora:</strong> {{ $appointment->time }}</p>

    <p style="margin-top:40px; font-size:11px; color:#888;">
        Generado por {{ config('app.name') }} — {{ now()->format('d/m/Y H:i') }}
    </p>

</body>
</html>