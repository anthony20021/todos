@extends('mails.layout.mail')
@section('content')
    <main>
        <p>Bonjour,<br> {{$owner['firstname']}} {{ $owner['name'] }} vous a partagé sa liste {{ $list_name }}</p>

        <p>À très vite !</p>
        <br>
        <p>Ce mail est un message automatique, merci de ne pas répondre.</p>
    </main>
@endsection
