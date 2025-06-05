<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>DNI</th>
                <th>PARTICIPANTE</th>
                <th>CURSO</th>
                <th>CORREO</th>
                <th>CODIGO</th>
                <th>PARTICIPÓ COMO</th>
                <th>PROGRAMA ID</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($participante as $item)
                <tr>
                    <td>{{ $item->id_participante }}</td>
                    <td>{{ $item->dni }}</td>
                    <td>{{ $item->nombre }} {{ $item->apellido }}</td>
                    <td>{{ $item->curso }}</td>
                    <td>{{ $item->correo }}</td>
                    <td>{{ $item->codigo }}</td>
                    <td>{{ $item->participo_como }}</td>
                    <td>{{ $item->id_programa }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
