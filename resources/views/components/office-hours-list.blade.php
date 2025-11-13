@php
    // helper kecil untuk ambil value dari beberapa kemungkinan key
    function getHourValue(array $hour, array $keys, $default = '')
    {
        foreach ($keys as $k) {
            if (isset($hour[$k]) && $hour[$k] !== null) {
                return $hour[$k];
            }
        }
        return $default;
    }
@endphp

@if (!empty($officeHours))
    <ul class="list-group">
        @foreach ($officeHours as $hour)
            @php
                $startDay = getHourValue($hour, ['start_day', 'day_start', 'dayStart']);
                $endDay = getHourValue($hour, ['end_day', 'day_end', 'dayEnd']);
                $startTime = getHourValue($hour, ['start_time', 'time_start', 'timeStart']);
                $endTime = getHourValue($hour, ['end_time', 'time_end', 'timeEnd']);
                $tz = getHourValue($hour, ['timezone', 'zone', 'tz'], '');
            @endphp

            @if (empty($startDay) && empty($startTime))
                @continue
            @endif

            <li class="list-group-item bg-light d-flex justify-content-between align-items-center">
                <span>
                    {{ ucfirst($startDay) }}
                    @if (!empty($endDay) && $startDay !== $endDay)
                        – {{ ucfirst($endDay) }}
                    @endif
                    , {{ $startTime }}–{{ $endTime }} {{ $tz }}
                </span>
            </li>
        @endforeach
    </ul>
@else
    <p class="text-muted">Belum ada jam operasional</p>
@endif
