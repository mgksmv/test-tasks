@extends('layouts.app')

@section('content')
    <div class="container">
        <tasks
            :tasks-data="{{ $tasks }}"
            :statuses="{{ $statuses }}"
        ></tasks>
    </div>
@endsection

@push('scripts')
    @vite('resources/js/home.js')
@endpush
