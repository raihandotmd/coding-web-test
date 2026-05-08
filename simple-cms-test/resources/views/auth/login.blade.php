@extends('layouts.app')

@section('title', 'Login')

@section('content')
    <div class="row justify-content-center mt-5">
        <div class="col-md-5">
            <div class="card">
                <div class="card-body">
                    <h3 class="mb-4">Login</h3>

                    <div id="alert" class="alert alert-danger d-none"></div>

                    <form id="loginForm">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $('#loginForm').on('submit', function(e) {
            e.preventDefault();
            const $alert = $('#alert').addClass('d-none');

            $.ajax({
                url: '{{ url('/login') }}',
                method: 'POST',
                data: $(this).serialize(), 
                success: function(res) {
                    window.location = res.data;
                },
                error: function(xhr) {
                    const msg = xhr.responseJSON?.message || 'Login gagal';
                    $alert.removeClass('d-none').text(msg);
                }
            });
        });
    </script>
@endpush
