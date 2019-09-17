@extends('layouts.main')
@section('content')
<div id="app" style="background-color: #eead00 !important;
min-height: 100vh !important;
overflow: scroll;">
    <app email="{{ Auth::user()->email }}" userid="{{ Auth::user()->id }}"></app>
</div>
@endsection
@section('stylesheets')

<!-- Compiled and minified CSS -->
    {{--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/css/materialize.min.css">  --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="{{ asset('css/bulma-divider.min.css') }}" rel="stylesheet"></link>
@endsection
@section('scripts')
    <!-- Compiled and minified JavaScript -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-rc.2/js/materialize.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="{{ asset('js/app.js') }}" defer></script>
@endsection
@section('error-scripts')
    @if ($errors->has('email'))

        <script>
            iziToast.error({
                title: `Error`,
                message: '{{ $errors->first('email') }}',
            });
        </script>
    @endif
    @if ($errors->has('name'))
        <script>
            iziToast.error({
                title: `Error`,
                message: '{{ $errors->first('name') }}',
            });
        </script>
    @endif
    @if ($errors->has('phone'))
        <script>
            iziToast.error({
                title: `Error`,
                message: '{{ $errors->first('phone') }}',
            });
        </script>
    @endif
    @if ($errors->has('password'))
        <script>
            iziToast.error({
                title: `Error`,
                message: '{{ $errors->first('password') }}',
            });
        </script>
    @endif
@endsection
