@extends('layout.masterAdmin')
@section('title', 'Data Workout')

@section('content')
    <main class="main-dashboard py-5 px-5">
        <div class="container">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold">Data Workout</h4>
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambah">
                    <i class="bi bi-plus-square"></i> Tambah Workout
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
                    <table class="table table-bordered table-hover table-sm" id="workoutTable">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Nama</th>
                                <th>Tingkat kesulitan</th>
                                <th>Kategori</th>
                                <th>Durasi</th>
                                <th>Alat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($workouts as $index => $workout)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $workout->nama_workout }}</td>
                                    <td>{{ $workout->tingkat_kesulitan }}</td>
                                    <td>{{ $workout->kategori }}</td>
                                    <td>{{ $workout->durasi }} menit</td>
                                    <td>{{ $workout->alat }}</td>
                                    <td>
                                        {{-- Detail, Edit, dan Hapus --}}
                                        <a href="#" class="btn btn-sm btn-success btn-detail" data-workout='@json($workout)' data-bs-toggle="modal" data-bs-target="#modalDetailWorkout">
                                            <i class="bi bi-zoom-in"></i> Detail
                                        </a>
                                        <a href="#" class="btn btn-sm btn-warning btn-edit" data-nama="{{ $workout->nama_workout }}" data-tingkat="{{ $workout->tingkat_kesulitan }}"
                                            data-kategori="{{ $workout->kategori }}" data-durasi="{{ $workout->durasi }}" data-alat="{{ $workout->alat }}" data-ilustrasi="{{ $workout->ilustrasi }}"
                                            data-deskripsi="{{ $workout->deskripsi }}" data-instruksi="{{ $workout->instruksi }}" data-id="{{ $workout->id }}" data-bs-toggle="modal"
                                            data-bs-target="#modalEdit">
                                            <i class="bi bi-pencil"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('adminWorkoutDelete', $workout->id) }}" class="d-inline" onsubmit="return confirm('Yakin ingin hapus?')">
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

    {{-- Modal Tambah Data --}}
    <div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form action="{{ route('adminWorkoutStore') }}" method="POST" enctype="multipart/form-data" class="modal-content">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Workout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <label>Nama Workout</label>
                        <input type="text" name="nama_workout" class="form-control" placeholder="Masukkan Nama Workout" required>
                    </div>
                    <div class="mb-2">
                        <label>Tingkat Kesulitan</label>
                        <select name="tingkat_kesulitan" class="form-control" required>
                            <option value="" disabled selected>Pilih tingkat kesulitan</option>
                            <option value="Pemula">Pemula</option>
                            <option value="Menengah">Menengah</option>
                            <option value="Ahli">Ahli</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Kategori</label>
                        <select name="kategori" class="form-control" required>
                            <option value="" disabled selected>Pilih kategori workout</option>
                            <option value="Kardio">Kardio</option>
                            <option value="Bodyweight Training">Bodyweight Training</option>
                            <option value="Fleksibilitas">Fleksibilitas</option>
                            <option value="Dance Fitness">Dance Fitness</option>
                            <option value="HIIT">HIIT</option>
                            <option value="Kekuatan">Kekuatan</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Durasi (menit)</label>
                        <input type="number" name="durasi" class="form-control" placeholder="Masukkan Durasi Workout" required>
                    </div>
                    <div class="mb-2">
                        <label>Alat</label>
                        <input type="text" name="alat" class="form-control" placeholder="Masukkan Alat Workout" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ilustrasi (gambar/video/gif)</label>

                        {{-- Preview --}}
                        <div id="preview-ilustrasi" class="mb-2 border rounded" style="width: 100%; max-height: 300px; overflow: hidden; text-align: center;">
                            <small class="text-muted">Preview akan muncul di sini</small>
                        </div>

                        {{-- File Input --}}
                        <input type="file" name="ilustrasi" id="input-ilustrasi" accept="image/*,video/*" class="form-control">
                    </div>

                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <input id="deskripsi" type="hidden" name="deskripsi" placeholder="Masukkan Deskripsi Workout">
                        <trix-editor input="deskripsi"></trix-editor>
                    </div>
                    <div class="mb-2">
                        <label>Instruksi</label>
                        <input id="instruksi" type="hidden" name="instruksi" placeholder="Masukkan Intruksi Workout">
                        <trix-editor input="instruksi"></trix-editor>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Edit Data --}}
    <div class="modal fade" id="modalEdit" tabindex="-1" aria-labelledby="modalEditLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form method="POST" class="modal-content" id="formEditWorkout" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Workout</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id" id="edit-id">
                    <div class="mb-2">
                        <label>Nama Workout</label>
                        <input type="text" name="nama_workout" id="edit-nama" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Tingkat Kesulitan</label>
                        <select name="tingkat_kesulitan" id="edit-tingkat" class="form-control" required>
                            <option value="Pemula">Pemula</option>
                            <option value="Menengah">Menengah</option>
                            <option value="Ahli">Ahli</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Kategori</label>
                        <select name="kategori" id="edit-kategori" class="form-control" required>
                            <option value="Kardio">Kardio</option>
                            <option value="Bodyweight Training">Bodyweight Training</option>
                            <option value="Fleksibilitas">Fleksibilitas</option>
                            <option value="Dance Fitness">Dance Fitness</option>
                            <option value="HIIT">HIIT</option>
                            <option value="Kekuatan">Kekuatan</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Durasi (menit)</label>
                        <input type="number" name="durasi" id="edit-durasi" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Alat</label>
                        <input type="text" name="alat" id="edit-alat" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ilustrasi (gambar/video/gif)</label>

                        {{-- Preview --}}
                        <div id="edit-preview-ilustrasi" class="mb-2 border rounded" style="width: 100%; max-height: 300px; overflow: hidden; text-align: center;">
                            <small class="text-muted">Preview akan muncul di sini</small>
                        </div>

                        {{-- File Input --}}
                        <input type="file" name="ilustrasi" id="edit-input-ilustrasi" accept="image/*,video/*" class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Deskripsi</label>
                        <input id="edit-deskripsi" type="hidden" name="deskripsi">
                        <trix-editor input="edit-deskripsi"></trix-editor>
                    </div>
                    <div class="mb-2">
                        <label>Instruksi</label>
                        <input id="edit-instruksi" type="hidden" name="instruksi">
                        <trix-editor input="edit-instruksi"></trix-editor>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary">Perbarui</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Detail Workout --}}
    <div class="modal fade" id="modalDetailWorkout" tabindex="-1" aria-labelledby="modalDetailWorkoutLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Workout <span id="detail-nama"></h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{-- Bagian kiri --}}
                        <div class="col-md-5 text-center mb-3 mb-md-0">
                            <h6 class="text-start"><b>Ilustrasi</b></h6>
                            <img id="detail-ilustrasi" src="" alt="Ilustrasi" class="img-fluid w-100 rounded mb-3" style="max-height: 300px; object-fit: cover;">
                            <p><b>Kategori:</b> <span id="detail-kategori"></span></p>
                            <p><b>Tingkat Kesulitan:</b> <span id="detail-tingkat"></span></p>
                            <p><b>Durasi:</b> <span id="detail-durasi"></span> menit</p>
                            <p><b>Alat:</b> <span id="detail-alat"></span></p>
                        </div>

                        {{-- Bagian kanan --}}
                        <div class="col-md-7">
                            <h6><b>Deskripsi</b></h6>
                            <div id="detail-deskripsi" class="trix-content"></div>

                            <h6><b>Instruksi</b></h6>
                            <div id="detail-instruksi" class="trix-content"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalDetail = new bootstrap.Modal(document.getElementById('modalDetailWorkout'));
            const modalEdit = new bootstrap.Modal(document.getElementById('modalEdit'));

            document.querySelectorAll('.btn-detail[data-workout]').forEach(button => {
                button.addEventListener('click', () => {
                    const workout = JSON.parse(button.dataset.workout);
                    const detailIlustrasi = document.getElementById('detail-ilustrasi');

                    if (workout.ilustrasi) {
                        const fullPath = workout.ilustrasi.startsWith('http') ? workout.ilustrasi : `/${workout.ilustrasi}`;
                        detailIlustrasi.src = fullPath;
                    } else {
                        detailIlustrasi.src = 'https://www.shutterstock.com/image-vector/default-ui-image-placeholder-wireframes-600nw-1037719192.jpg';
                    }

                    document.getElementById('detail-nama').textContent = workout.nama_workout || '-';
                    document.getElementById('detail-kategori').textContent = workout.kategori || '-';
                    document.getElementById('detail-tingkat').textContent = workout.tingkat_kesulitan || '-';
                    document.getElementById('detail-durasi').textContent = workout.durasi || '-';
                    document.getElementById('detail-alat').textContent = workout.alat || '-';
                    document.getElementById('detail-deskripsi').innerHTML = workout.deskripsi || '-';
                    document.getElementById('detail-instruksi').innerHTML = workout.instruksi || '-';

                    modalDetail.show();
                });
            });

            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', () => {
                    const id = button.dataset.id;

                    document.getElementById('edit-id').value = id;
                    document.getElementById('edit-nama').value = button.dataset.nama || '';
                    document.getElementById('edit-tingkat').value = button.dataset.tingkat || '';
                    document.getElementById('edit-kategori').value = button.dataset.kategori || '';
                    document.getElementById('edit-durasi').value = button.dataset.durasi || '';
                    document.getElementById('edit-alat').value = button.dataset.alat || '';
                    document.getElementById('edit-deskripsi').value = button.dataset.deskripsi || '';
                    document.querySelector('trix-editor[input="edit-deskripsi"]').editor.loadHTML(button.dataset.deskripsi || '');

                    document.getElementById('edit-instruksi').value = button.dataset.instruksi || '';
                    document.querySelector('trix-editor[input="edit-instruksi"]').editor.loadHTML(button.dataset.instruksi || '');

                    const ilustrasi = button.dataset.ilustrasi;
                    const ilustrasiPreview = document.getElementById('edit-preview-ilustrasi');

                    if (ilustrasi) {
                        const fullPath = ilustrasi.startsWith('http') ? ilustrasi : `/${ilustrasi}`;
                        if (fullPath.match(/\.(mp4|webm|ogg)$/)) {
                            ilustrasiPreview.innerHTML = `<video src="${fullPath}" class="img-fluid" style="max-height:300px;" controls></video>`;
                        } else {
                            ilustrasiPreview.innerHTML = `<img src="${fullPath}" class="img-fluid" style="max-height:300px;" />`;
                        }
                    } else {
                        ilustrasiPreview.innerHTML = '<small class="text-muted">Preview akan muncul di sini</small>';
                    }

                    // Atur action form
                    document.getElementById('formEditWorkout').action = `/admin/workout/${id}`;
                });
            });

        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('input-ilustrasi');
            const previewContainer = document.getElementById('preview-ilustrasi');

            input.addEventListener('change', function(e) {
                const file = e.target.files[0];

                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const src = e.target.result;
                    let previewElement;

                    // Cek tipe file
                    if (file.type.startsWith('image/')) {
                        previewElement = document.createElement('img');
                        previewElement.src = src;
                        previewElement.className = "img-fluid";
                        previewElement.style.maxHeight = "300px";
                    } else if (file.type.startsWith('video/')) {
                        previewElement = document.createElement('video');
                        previewElement.src = src;
                        previewElement.controls = true;
                        previewElement.className = "img-fluid";
                        previewElement.style.maxHeight = "300px";
                    } else {
                        previewElement = document.createElement('p');
                        previewElement.textContent = "Format tidak didukung";
                    }

                    // Kosongkan & tampilkan preview
                    previewContainer.innerHTML = '';
                    previewContainer.appendChild(previewElement);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Preview edit
            const editInput = document.getElementById('edit-input-ilustrasi');
            const editPreview = document.getElementById('edit-preview-ilustrasi');

            editInput.addEventListener('change', function(e) {
                const file = e.target.files[0];
                if (!file) return;

                const reader = new FileReader();
                reader.onload = function(e) {
                    const src = e.target.result;
                    let previewElement;

                    if (file.type.startsWith('image/')) {
                        previewElement = document.createElement('img');
                        previewElement.src = src;
                        previewElement.className = "img-fluid";
                        previewElement.style.maxHeight = "300px";
                    } else if (file.type.startsWith('video/')) {
                        previewElement = document.createElement('video');
                        previewElement.src = src;
                        previewElement.controls = true;
                        previewElement.className = "img-fluid";
                        previewElement.style.maxHeight = "300px";
                    } else {
                        previewElement = document.createElement('p');
                        previewElement.textContent = "Format tidak didukung";
                    }

                    editPreview.innerHTML = '';
                    editPreview.appendChild(previewElement);
                };

                reader.readAsDataURL(file);
            });
        });
    </script>
    <script>
        document.addEventListener("trix-attachment-add", function(event) {
            if (event.attachment.file) {
                uploadFile(event.attachment);
            }
        });

        function uploadFile(attachment) {
            const file = attachment.file;
            const formData = new FormData();
            formData.append("image", file);

            fetch("{{ route('trix.image.upload') }}", {
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(result => {
                    attachment.setAttributes({
                        url: result.url,
                        href: result.url
                    });
                })
                .catch(error => {
                    console.error("Upload failed:", error);
                });
        }
    </script>
@endpush
