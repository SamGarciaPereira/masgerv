<!DOCTYPE html>
<html lang="pt-pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>{{ $anexo->nome_original }}</title>
    
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0e0e0e; 
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            overflow: hidden;
        }
        img {
            max-width: 100%;
            max-height: 100vh;
            object-fit: contain; 
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
        }
    </style>
</head>
<body>
    <img src="{{ route('anexos.show', ['anexo' => $anexo->id, 'filename' => $filename, 'raw' => 1]) }}" 
         alt="{{ $anexo->nome_original }}">
</body>
</html>