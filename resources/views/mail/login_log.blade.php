<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login log</title>
</head>
<body>
<p>User Login at {{ Carbon\Carbon::now() }}</p>
<table>
    <tr>
        <th>Name</th>
        <th>:</th>
        <td>
            {{ $user->name }}
        </td>
    </tr>
    <tr>
        <th>Email</th>
        <th>:</th>
        <td>{{ $user->email }}</td>
    </tr>
</table>

</body>
</html>
