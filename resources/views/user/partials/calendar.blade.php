<div class="d-flex justify-content-between align-items-center mb-3">
    @php
        $prevMonth = $currentMonth->copy()->subMonth()->format('Y-m');
        $nextMonth = $currentMonth->copy()->addMonth()->format('Y-m');
        $selectedMonth = $currentMonth->format('m');
        $selectedYear = $currentMonth->format('Y');
    @endphp
    {{-- Tombol kiri --}}
    <button class="btn btn-sm btn-outline-secondary btn-change-month" data-month="{{ $prevMonth }}">&laquo;</button>

    {{-- Dropdown Bulan & Tahun --}}
    <div class="d-flex gap-2 align-items-center">
        <select id="select-month" class="form-select form-select-sm">
            @foreach (range(1, 12) as $m)
                <option value="{{ sprintf('%02d', $m) }}" {{ $m == (int) $selectedMonth ? 'selected' : '' }}>
                    {{ Carbon\Carbon::create()->month($m)->translatedFormat('F') }}
                </option>
            @endforeach
        </select>

        <select id="select-year" class="form-select form-select-sm">
            @for ($y = now()->year - 5; $y <= now()->year + 10; $y++)
                <option value="{{ $y }}" {{ $y == (int) $selectedYear ? 'selected' : '' }}>{{ $y }}</option>
            @endfor
        </select>
    </div>

    {{-- Tombol kanan --}}
    <button class="btn btn-sm btn-outline-secondary btn-change-month" data-month="{{ $nextMonth }}">&raquo;</button>
</div>

<table class="table table-bordered text-center mb-0 calendar-table">
    <thead class="table-light">
        <tr>
            <th>Min</th>
            <th>Sen</th>
            <th>Sel</th>
            <th>Rab</th>
            <th>Kam</th>
            <th>Jum</th>
            <th>Sab</th>
        </tr>
    </thead>
    <tbody>
        @php
            $today = Carbon\Carbon::today();
            $start = $currentMonth->copy()->startOfMonth();
            $end = $currentMonth->copy()->endOfMonth();
            $day = $start->copy()->startOfWeek(Carbon\Carbon::SUNDAY);
        @endphp

        @while ($day <= $end || $day->dayOfWeek != Carbon\Carbon::SUNDAY)
            <tr>
                @for ($i = 0; $i < 7; $i++)
                    @php
                        $dateString = $day->format('Y-m-d');
                        $isCurrentMonth = $day->month == $currentMonth->month;
                        $isDone = isset($progress[$dateString]) && $progress[$dateString];
                    @endphp
                    <td data-date="{{ $dateString }}" 
                        class="{{ !$isCurrentMonth ? 'text-muted' : '' }}
                                {{ $isDone ? 'bg-success text-white' : '' }}">
                        <span class="{{ $isDone && $dateString == now()->toDateString() ? 'fw-bold' : ($isDone ? 'fw-normal' : '') }}">
                            {{ $day->day }}
                        </span>
                        {{-- {{ $day->day }} --}}
                    </td>
                    @php $day->addDay(); @endphp
                @endfor
            </tr>
        @endwhile
    </tbody>
</table>
