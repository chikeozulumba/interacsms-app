@extends('layouts.common')
@section('content')
<div id="verify">
    <verify email="{{ Auth::user()->email }}"></verify>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('js/verify.js') }}" defer></script>
@endsection
