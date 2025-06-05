<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Código de verificación</title>
    <style>
        body {
            background-color: #f7f7f7;
            font-family: Arial, sans-serif;
            color: #333;
        }

        .container {
            width: 80%;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        }

        h1 {
            margin-top: 0;
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        p {
            font-size: 16px;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        code {
            background-color: #f2f2f2;
            padding: 5px;
            border-radius: 3px;
            font-family: monospace;
            font-size: 30px
        }
    </style>
</head>

<body>
    <div class="container">
        <h1>Código de verificación</h1>
        <p>Por favor, utilice el siguiente código para obtener su Certificado:</p>
        <code>{{ $codigo }}</code>
        <p>Si no ha solicitado este código, por favor ignore este correo electrónico.</p>
    </div>
</body>

</html>
