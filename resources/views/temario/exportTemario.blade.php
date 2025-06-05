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
                <th>CURSO</th>
                <th>TEMA</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($temario as $item)
                <tr>
                    <td>{{ $item->id_temario }}</td>
                    <td>{{ $item->nombre }}</td>
                    <td>{{ $item->tema }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
