<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Comprobante de Cita Médica</title>
    <style>
        body { font-family: sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .details { margin: 0 auto; width: 80%; border-collapse: collapse; }
        .details th, .details td { border: 1px solid #ccc; padding: 10px; text-align: left; }
        .details th { background-color: #f4f4f4; width: 30%; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Comprobante de Cita Médica</h2>
    </div>
    <table class="details">
        <tr>
            <th>Paciente:</th>
            <td>{{ $appointment->patient->name }}</td>
        </tr>
        <tr>
            <th>Doctor:</th>
            <td>Dr/a. {{ $appointment->doctor->user->name }}</td>
        </tr>
        <tr>
            <th>Fecha y Hora:</th>
            <td>{{ $appointment->appointment_date->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <th>Motivo:</th>
            <td>{{ $appointment->reason ?? 'No especificado' }}</td>
        </tr>
        <tr>
            <th>Estado:</th>
            <td>{{ ucfirst($appointment->status) }}</td>
        </tr>
    </table>
</body>
</html>
