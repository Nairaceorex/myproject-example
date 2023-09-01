<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    
    @auth
    <p>Congrat</p>
    <form action="/logout" method="POST">
        @csrf
        <button>Log out</button>
    </form>

    <div style="border: 3px solid black;">
        <div>
            @csrf
            <label for="token">Access Token</label>
            <input type="text" name="token" value="{{ $token }}" readonly>
        </div>
        <form action="/update-token" method="POST">
            @csrf
            <button type="submit">Update Token</button>
        </form>
        
    </div>

    @else
        
    <div style="border: 3px solid black;">
        <h2>Register</h2>
        <form id="register-form" action="/register" method="POST">
            @csrf
            <input name="name" type="text" placeholder="name">
            <input name="email" type="text" placeholder="email">
            <input name="password" type="password" placeholder="password">
            <button>Register</button>
        </form>
    </div>

    <div style="border: 3px solid black;">
        <h2>Login</h2>
        <form action="/login" method="POST">
            @csrf
            <input name="loginname" type="text" placeholder="name">
            <input name="loginpassword" type="password" placeholder="password">
            <button>Log in</button>
        </form>
    </div>

    @endauth

    
</body>
</html>