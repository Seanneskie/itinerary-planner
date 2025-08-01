@props(['entries'])
<div class="mt-6">
    <canvas id="budget-category-chart" height="200"></canvas>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const ctx = document.getElementById('budget-category-chart');
        const data = @json($entries->groupBy('category')->map(fn($items) => $items->sum('amount')));
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: Object.keys(data),
                datasets: [{
                    data: Object.values(data),
                    backgroundColor: ['#60a5fa', '#34d399', '#fbbf24', '#f87171', '#a78bfa'],
                }]
            }
        });
    });
</script>
@endpush
