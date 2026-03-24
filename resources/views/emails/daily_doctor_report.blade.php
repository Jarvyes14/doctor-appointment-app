<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body>
    <h2>Tus Citas Programadas para Hoy</h2>
    <p>Hola Dr/a. <strong>{{ $doctorName }}</strong>,</p>
    <p>A continuación se presenta el resumen de sus citas para el día de hoy, <strong>{{ \Carbon\Carbon::today()->format('d/m/Y') }}</strong>:</p>

    @if($appointments->isEmpty())
        <p>No tienes citas programadas para el día de hoy.</p>
    @else
        <table border="1" cellpadding="10" cellspacing="0" style="border-collapse: collapse; width: 100%;">
            <thead>
                <tr>
                    <th style="background-color: #f4f4f4;">Hora</th>
                    <th style="background-color: #f4f4f4;">Paciente</th>
                    <th style="background-color: #f4f4f4;">Motivo</th>
                    <th style="background-color: #f4f4f4;">Estado</th>
                </tr>
            </thead>
            <tbody>
                @foreach($appointments as $appointment)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($appointment->appointment_date)->format('H:i') }}</td>
                        <td>{{ $appointment->patient->name }}</td>
                        <td>{{ $appointment->reason ?? 'N/A' }}</td>
                        <td>{{ ucfirst($appointment->status) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <br>
    <p>Que tenga un excelente día,<br>El Sistema</p>
</body>
</html>
