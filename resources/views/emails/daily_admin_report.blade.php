<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Reporte Diario de Citas Médico</h2>
    <p>Hola Administrador,</p>
    <p>A continuación se presenta la lista de citas agendadas para hoy, <strong>{{ \Carbon\Carbon::today()->format('d/m/Y') }}</strong>:</p>

    @if($appointments->isEmpty())
        <p>No hay citas programadas para el día de hoy.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="background-color: #f4f4f4;">Hora</th>
                    <th style="background-color: #f4f4f4;">Paciente</th>
                    <th style="background-color: #f4f4f4;">Doctor</th>
                    <th style="background-color: #f4f4f4;">Motivo</th>
                    <th style="background-color: #f4f4f4;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</td>
                        <td>{{ $appointment->patient->name }}</td>
                        <td>Dr/a. {{ $appointment->doctor->user->name }}</td>
                        <td>{{ $appointment->reason ?? 'N/A' }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <br>
    <p>Saludos,<br>El Sistema</p>
</body>
</html>
