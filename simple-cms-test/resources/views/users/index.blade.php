@extends('layouts.app')

@section('title', 'User List')

@section('content')
    <div class="d-flex justify-content-between mb-3">
        <h2>Users</h2>
        <div>
            <button class="btn btn-primary" id="btnAdd">+ Add User</button>
            <button class="btn btn-secondary" id="btnLogout">Logout</button>
        </div>
    </div>

    <table id="usersTable" class="table table-bordered" style="width:100%">
        <thead>
            <tr>
                <th>Email</th>
                <th>Nama</th>
                <th>Image</th>
                <th width="150">Action</th>
            </tr>
        </thead>
    </table>
    <div class="modal fade" id="userModal" tabindex="-1">
        <div class="modal-dialog">
            <form id="userForm" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div id="formError" class="alert alert-danger d-none"></div>
                        <input type="hidden" name="id" id="userId">
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password <small id="pwdHint" class="text-muted d-none">(kosongin
                                    kalo gak diganti)</small></label>
                            <input type="password" name="password" id="password" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Profile Image</label>
                            <input type="file" name="profile_image" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let table;

        $(function() {
            table = $('#usersTable').DataTable({
                serverSide: true,
                processing: true,
                ajax: '{{ route('users.data') }}',
                pageLength: 10,
                columns: [{
                        data: 'email'
                    },
                    {
                        data: 'name'
                    },
                    {
                        data: 'profile_image',
                        orderable: false,
                        searchable: false,
                        render: function(d) {
                            if (!d) return '<span class="text-muted">No image</span>';
                            return `<img src="/storage/${d}" width="200" height="200" style="object-fit:cover">`;
                        }
                    },
                    {
                        data: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(id) {
                            return `
                          <button class="btn btn-sm btn-warning btn-edit" data-id="${id}">Edit</button>
                          <button class="btn btn-sm btn-danger btn-delete" data-id="${id}">Delete</button>
                      `;
                        }
                    }
                ]
            });

            $('#btnLogout').on('click', function() {
                $.post('{{ route('logout') }}', function() {
                    window.location = '{{ route('login') }}';
                });
            });

            const modal = new bootstrap.Modal('#userModal');

            $('#btnAdd').on('click', function() {
                $('#userForm')[0].reset();
                $('#userId').val('');
                $('#email').prop('required', true);
                $('#password').prop('required', true);
                $('#pwdHint').addClass('d-none');
                $('#modalTitle').text('Add User');
                $('#formError').addClass('d-none');
                modal.show();
            });

            $('#usersTable').on('click', '.btn-edit', function() {
                const id = $(this).data('id');
                $.get(`/users/${id}`, function(user) {
                    $('#userForm')[0].reset();
                    $('#userId').val(user.id);
                    $('#email').val(user.email).prop('readonly', true);
                    $('#name').val(user.name);
                    $('#password').prop('required', false);
                    $('#pwdHint').removeClass('d-none');
                    $('#modalTitle').text('Edit User');
                    $('#formError').addClass('d-none');
                    modal.show();
                });
            });

            $('#userForm').on('submit', function(e) {
                e.preventDefault();
                const id = $('#userId').val();
                const url = id ? `/users/${id}` : '/users';
                const fd = new FormData(this);

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    success: function() {
                        modal.hide();
                        table.ajax.reload();
                    },
                    error: function(xhr) {
                        let msg = 'Gagal menyimpan';
                        if (xhr.status === 422) {
                            msg = Object.values(xhr.responseJSON.errors).flat().join('<br>');
                        }
                        $('#formError').removeClass('d-none').html(msg);
                    }
                });
            });

            $('#usersTable').on('click', '.btn-delete', function() {
                if (!confirm('Yakin hapus user ini?')) return;
                const id = $(this).data('id');
                $.ajax({
                    url: `/users/${id}`,
                    method: 'DELETE',
                    success: function() {
                        table.ajax.reload();
                    }
                });
            });
        });
    </script>
@endpush
