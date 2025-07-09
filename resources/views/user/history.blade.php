@extends('layout.master')
@section('title', 'FitQ Riwayat Pengguna')

@section('content')

    <main class="main-instruksi py-5">
        <div class="container">
            <div class="row mb-4">
                <div class="col-12">
                    <h4 class="fw-bold">Riwayat Pengguna</h4>
                </div>
            </div>

            <div class="card shadow-sm p-4 mb-1" style="background-color: white;">
                <div class="table-responsive" style="font-size: 15px;">
                    <table class="table table-bordered table-hover table-sm" id="historyTable">
                        <thead class="table-light">
                            <tr>
                                <th>No.</th>
                                <th>Workout</th>
                                <th>Tanggal</th>
                                <th>Mood</th>
                                <th>Strategi</th>
                                <th>Feedback</th>
                                <th>Snapshot State</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($histories as $index => $history)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ $history->workout->nama_workout }}</td>
                                    <td>{{ $history->tanggal }}</td>
                                    <td>{{ $history->mood }}</td>
                                    <td>{{ $history->strategi ?? '-' }}</td>
                                    <td>
                                        @if ($history->feedback === 2)
                                            Pas
                                        @elseif ($history->feedback === 1)
                                            Terlalu Ringan
                                        @elseif ($history->feedback === -2)
                                            Terlalu Berat
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if ($history->state_snapshot)
                                            <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#snapshotModal{{ $history->id }}"><i class="bi bi-eye"></i> Lihat</button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Modal State --}}
            @foreach ($histories as $history)
                @if ($history->state_snapshot)
                    <div class="modal fade" id="snapshotModal{{ $history->id }}" tabindex="-1" aria-labelledby="snapshotModalLabel{{ $history->id }}" aria-hidden="true">
                        <div class="modal-dialog modal-lg mt-5">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Snapshot State</h5>
                                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Tutup"></button>
                                </div>
                                <div class="modal-body">
                                    <h4><b>State:</b></h4>
                                    <div class="form-text text-muted mt-0 mb-3" style="font-size: 10px;">
                                        <i class="bi bi-info-circle"></i> Kumpulan state atau keadaan yang digunakan algoritma Q-Learning.
                                    </div>
                                    <pre style="white-space: pre-wrap;">{{ $history->state_snapshot }}</pre>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    </main>

@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            $('#historyTable').DataTable({
                responsive: true,
                pageLength: 10,
                ordering: true,
                language: {
                    search: "Cari:",
                    paginate: {
                        previous: "Sebelumnya",
                        next: "Berikutnya"
                    },
                    zeroRecords: "Tidak ditemukan data yang cocok",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data"
                }
            });
        });
    </script>
