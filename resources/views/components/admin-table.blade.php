@props([
    'headers' => [],
])

<div class="table-responsive bg-white rounded-4 shadow-sm">
    <table class="table table-hover align-middle mb-0 custom-table">
        <thead class="bg-light text-muted small fw-semibold text-uppercase">
            <tr>
                @foreach ($headers as $header)
                    <th scope="col" class="py-3 px-4">{{ $header }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody class="border-top-0">
            {{ $slot }}
        </tbody>
    </table>
</div>
