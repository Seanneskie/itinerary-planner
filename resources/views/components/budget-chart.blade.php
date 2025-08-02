@props(['entries'])
<div class="mt-6">
    <canvas id="budget-chart" height="150"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('budget-chart');
        const data = @json($entries->sortBy('entry_date')->map(fn($e) => [
            'date' => $e->entry_date->format('Y-m-d'),
            'amount' => (float) ($e->spent_amount ?? 0),
        ])->values());

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Spent',
                    data: data.map(d => d.amount),
                    borderColor: '#60a5fa',
                    backgroundColor: '#60a5fa33',
                    fill: false,
                    tension: 0.4,
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush
