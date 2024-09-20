@extends('mails.layout.mail')
@section('content')
    <main>
        <p>Bonjour {{$firstname}},</p>
        <br>
        <p>Votre code de changement de mot de passe est le suivant : <h1><strong>{{$verif_code}}</strong></h1></p>
        <br>
        <p>Ce mail est un message automatique, merci de ne pas répondre.</p>
    </main>
@endsection
