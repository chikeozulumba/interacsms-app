@extends('layouts.common')
@section('content')
@routes
<div id="auth">
    <auth></auth>
</div>
@endsection
@section('scripts')
    <script src="{{ asset('js/auth.js') }}" defer></script>
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
    @if ($errors->has('error'))
        <script>
            iziToast.error({
                title: `Error`,
                message: '{{ $errors->first('error') }}',
            });
        </script>
    @endif
    @if ($errors->has('success'))
        <script>
            iziToast.success({
                title: `Success`,
                message: '{{ $errors->first('success') }}',
            });
        </script>
    @endif
@endsection
