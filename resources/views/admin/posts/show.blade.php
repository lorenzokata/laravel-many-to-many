@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Dettagli Prodotto</h2>
        <div class="card">
            <div class="card-header">
                {{ $post->title }}
            </div>
            <div class="card-body">
                <h5 class="card-title">{{ $post->slug }}</h5>
                <p class="card-text">{{ $post->content }}</p>
            </div>
        </div>
        <a href="{{ route('admin.posts.index')}}" class="btn btn-danger">Torna Indietro</a>
    </div>
@endsection