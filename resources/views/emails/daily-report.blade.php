<!DOCTYPE html>
<html>
<body style="font-family: sans-serif; color: #333; padding: 30px;">

    <h2 style="color: #2d6a4f;">Reporte de Citas — {{ now()->format('d/m/Y') }}</h2>
    <p>Hola <strong>{{ $recipientName }}</strong>, estas son las citas programadas para hoy:</p>

    <table style="width:100%; border-collapse:collapse; margin-top:20px;">
        <thead>
            <tr style="background:#2d6a4f; color:white;">
                <th style="padding:10px; text-align:left;">Hora</th>
                <th style="padding:10px; text-align:left;">Paciente</th>
                <th style="padding:10px; text-align:left;">Doctor</th>
                <th style="padding:10px; text-align:left;">Especialidad</th>
                <th style="padding:10px; text-align:left;">Motivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($appointments as $i => $appointment)
            <tr style="background: {{ $i % 2 === 0 ? '#f9f9f9' : '#ffffff' }}">
                <td style="padding:8px; border:1px solid #ddd;">{{ \Carbon\Carbon::parse($appointment->start_time)->format('H:i') }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->patient->user->name }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->doctor->user->name }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->doctor->speciality->name ?? 'N/A' }}</td>
                <td style="padding:8px; border:1px solid #ddd;">{{ $appointment->reason }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top:20px;"><strong>Total de citas:</strong> {{ $appointments->count() }}</p>
    <p style="color:#888; font-size:12px;">— {{ config('app.name') }}</p>

</body>
</html>