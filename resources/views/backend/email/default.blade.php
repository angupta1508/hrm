<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $mailData['subject'] }}</title>
</head>
<body>
    Hello <b>{{ $mailData['name'] }}</b>,
    
    {!! $mailData['body'] !!}
</body>
</html>