@extends('user.layout.app-master')
@section('title', 'Invitation - Brookfield Properties')
@section('content')

<section class="page">
    <div class="cover" data-image="assets/images/photos/parallax.jpg">
        <div class="page-top">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <h1>Invitation</h1>
                    </div>
                    <div class="col-lg-12 text-center">
                        <ol class="breadcrumb">
                            <li><a href="{{ url('/dashboard') }}">Dashboard</a></li>
                            <li class="active">Invitation</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="page-bottom pbottom-70">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6 text-center">
                    <div class="invitation-image mb-3">
                        <img src="{{ asset('images/download (3).png') }}" alt="Invitation Image" class="img-fluid">
                    </div>
                    <h2>Invitation Code</h2>
                    <div class="invitation-code-container mb-3">
                        <p id="invitation-code" class="lead">{{ $invitationCode }}</p>
                    </div>
                    <button class="btn btn-primary" onclick="copyCode()">Copy Invitation Code</button>
                </div>
            </div>
        </div>
    </div>
</section>

<script>
    function copyCode() {
        const code = document.getElementById('invitation-code');
        const textArea = document.createElement('textarea');
        textArea.value = code.innerText;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        alert('Invitation code copied to clipboard!');
    }
</script>

@include('user.layout.chat')
@endsection
