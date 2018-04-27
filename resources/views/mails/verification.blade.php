<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Radius Email Verification </title>
</head>
<body>
<img src="https://radiusfashion.com/logo_radius.png" alt="Radius Fashion Logo" width="150px" height="150px"><br>
<h1>Radius Email Verification</h1><br>
Thanks for registering with us, please click the link below to confirm your email adress.<br><br>
<span hidden>{{$code}}</span>
<a href="https://radiusfashion.com/setup/email?code={{$code}}" style="color:white;text-decoration: none;line-height: 100px">
    <div style="background-color:#f57f17;width:300px;height:100px;border-radius:5px;text-align:center">
        <h2>Click Here</h2></div>
</a>
</body>
</html>