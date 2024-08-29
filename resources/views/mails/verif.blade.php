@extends('mails.layout.mail')
@section('content')
    <main>
        <p>Bonjour {{$firstname}},</p>
        <br>
        <p>Votre code de confirmation est le suivant : <h1><strong>{{$verif_code}}</strong></h1></p>
        <br>
        <p>Vous pouvez confirmer votre compte lors de connection à votre espace personnel <a href="https://todos.website/login">ici</a>.</p>
        <p>À très vite !</p>
        <br>
        <p>Ce mail est un message automatique, merci de ne pas répondre.</p>
    </main>
@endsection
