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
            'amount' => (float) $e->spent_amount,
        ]));

        const colors = ['#60a5fa', '#34d399', '#fbbf24', '#f87171', '#a78bfa'];

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Spent',
                    data: data.map(d => d.amount),
                    backgroundColor: data.map((_, i) => colors[i % colors.length]),
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
