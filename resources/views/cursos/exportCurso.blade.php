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
                <th>NOMBRE</th>
                <th>HORAS</th>
                <th>DESCRIPCION</th>
                <th>INICIO</th>
                <th>TERMINO</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($curso as $item)
                <tr>
                    <td>{{ $item->id_curso }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->horas }}</td>
                    <td>{{ $item->descripcion }}</td>
                    <td>{{ $item->inicio }}</td>
                    <td>{{ $item->termino }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
