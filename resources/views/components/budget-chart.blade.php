@props(['entries'])
<div class="mt-6">
    <canvas id="budget-chart" height="200"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('budget-chart');
        const data = @json($entries->sortBy('entry_date')->map(fn($e) => [
            'date' => $e->entry_date->format('Y-m-d'),
            'amount' => (float) $e->amount,
        ]));
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.map(d => d.date),
                datasets: [{
                    label: 'Amount',
                    data: data.map(d => d.amount),
                    backgroundColor: '#60a5fa',
                }]
            },
            options: {
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    });
</script>
@endpush
