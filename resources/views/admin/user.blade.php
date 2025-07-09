@extends('layout.masterAdmin')
@section('title', 'Data User')

@section('content')
    <main class="main-dashboard py-5 px-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Data User</h4>
                <a href="#" class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTambahAdmin">
                    <i class="bi bi-plus-square"></i> Tambah Admin
                </a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show text-center shadow" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card shadow-sm p-4" style="background-color: white;">
                <div class="table-responsive" style="font-size: 15px;">
                    <table class="table table-bordered table-hover table-sm" id="userTable">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>Jenis Kelamin</th>
                                <th>Role</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $index => $user)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $user->username }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->jenis_kelamin }}</td>
                                    <td>{{ $user->roles->pluck('name')->implode(', ') }}</td>
                                    <td>
                                        {{-- Tombol Edit & Hapus --}}
                                        <a href="#" class="btn btn-sm btn-warning btn-edit-user" data-id="{{ $user->id }}" data-username="{{ $user->username }}" data-email="{{ $user->email }}"
                                            data-jenis="{{ $user->jenis_kelamin }}" data-bs-toggle="modal" data-bs-target="#modalEditUser">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('adminUserDelete', $user->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin hapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

    {{-- Modal Tambah Admin --}}
    <div class="modal fade" id="modalTambahAdmin" tabindex="-1" aria-labelledby="modalTambahAdminLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('adminUserStore') }}" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Username</label>
                        <input type="text" name="username" class="form-control" placeholder="Masukkan Username" required>
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" placeholder="Masukkan Email" required>
                    </div>
                    <div class="mb-2">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" class="form-control" required>
                            <option value="" disabled selected>Pilih Jenis Kelamin</option>
                            <option value="laki-laki">Laki-laki</option>
                            <option value="perempuan">Perempuan</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control" placeholder="Masukkan Password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
    {{-- Modal Edit User --}}
    <div class="modal fade" id="modalEditUser" tabindex="-1" aria-labelledby="modalEditUserLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" class="modal-content" id="formEditUser">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit User</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-user-id">
                    <div class="mb-2">
                        <label>Username</label>
                        <input type="text" name="username" id="edit-username" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Email</label>
                        <input type="email" name="email" id="edit-email" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Jenis Kelamin</label>
                        <select name="jenis_kelamin" id="edit-jenis" class="form-control" required>
                            <option value="Laki-laki">Laki-laki</option>
                            <option value="Perempuan">Perempuan</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Inisialisasi datatable
            $('#userTable').DataTable();

            // isi data modal edit
            document.querySelectorAll('.btn-edit-user').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;
                    document.getElementById('edit-user-id').value = id;
                    document.getElementById('edit-username').value = button.dataset.username;
                    document.getElementById('edit-email').value = button.dataset.email;
                    document.getElementById('edit-jenis').value = button.dataset.jenis;
                    document.getElementById('formEditUser').action = `/admin/user/${id}`;
                });
            });
        });
    </script>
@endpush
