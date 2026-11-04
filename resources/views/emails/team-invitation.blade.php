<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <p>Bonjour {{ $invitation->user->name }},</p>

    <p>
        {{ $invitation->inviter->name }}
        vous invite à rejoindre l’équipe de {{ $invitation->inviter->name }}.
    </p>

    <a href="{{ route('team-invitations.accept', $invitation) }}">
        Accepter l’invitation
    </a>
</body>
</html>