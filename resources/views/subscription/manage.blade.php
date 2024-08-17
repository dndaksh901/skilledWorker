@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Manage Subscription</h1>
        @livewire('manage-subscription', ['vendor' => $vendor])
    </div>
@endsection
