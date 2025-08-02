{{-- Weather Forecast Component --}}
@props(['itinerary'])

@php
    $pins = $itinerary->activities
        ->map(fn($activity) => ['latitude' => $activity->latitude, 'longitude' => $activity->longitude])
        ->filter(fn($loc) => $loc['latitude'] && $loc['longitude'])
        ->values();
    $start = $itinerary->start_date->toDateString();
    $end = $itinerary->end_date->toDateString();
@endphp

<div x-data='weatherForecast("{{$start}}","{{$end}}", @json($pins))' class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Weather Forecast</h3>
    <template x-if="error">
        <p class="text-sm text-red-600" x-text="error"></p>
    </template>
    <template x-if="!error && forecast.length === 0">
        <p class="text-sm text-gray-500 dark:text-gray-400">No rainy hours forecasted.</p>
    </template>
    <template x-for="city in forecast" :key="city.city">
        <div class="mb-6" x-show="city.days.length">
            <h4 class="font-medium text-gray-700 dark:text-gray-200 mb-2" x-text="city.city"></h4>
            <div class="grid md:grid-cols-2 gap-4">
                <template x-for="day in city.days" :key="day.date">
                    <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                        <h5 class="font-medium text-gray-700 dark:text-gray-200 mb-2" x-text="day.dateFormatted"></h5>
                        <template x-for="hour in day.hours" :key="hour.time">
                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-300">
                                <span x-text="hour.time"></span>
                                <span x-text="hour.precipitation + '% chance'"></span>
                            </div>
                        </template>
                    </div>
                </template>
            </div>
        </div>
    </template>
</div>

<script>
function weatherForecast(startDate, endDate, locations){
    return {
        error: '',
        forecast: [],
        init(){
            if(!locations.length){
                this.error = 'No locations available for weather forecast.';
                return;
            }
            const seen = {};
            Promise.all(locations.map(pin => {
                return fetch(`https://geocoding-api.open-meteo.com/v1/reverse?latitude=${pin.latitude}&longitude=${pin.longitude}&count=1`)
                    .then(r => r.json())
                    .then(data => {
                        if(!data.results || !data.results.length){
                            throw new Error('Location not found.');
                        }
                        const { latitude, longitude, name, timezone } = data.results[0];
                        if(seen[name]) return null;
                        seen[name] = true;
                        const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&hourly=precipitation_probability,weathercode&start_date=${startDate}&end_date=${endDate}&timezone=${encodeURIComponent(timezone)}`;
                        return fetch(url)
                            .then(r => r.json())
                            .then(data => {
                                const hours = data.hourly.time.map((t, i) => ({
                                    time: new Date(t).toLocaleTimeString([], {hour:'2-digit', minute:'2-digit'}),
                                    date: t.slice(0,10),
                                    precipitation: data.hourly.precipitation_probability[i]
                                }));
                                const grouped = {};
                                hours.forEach(h => {
                                    if(h.precipitation >= 50){
                                        if(!grouped[h.date]) grouped[h.date] = [];
                                        grouped[h.date].push({time: h.time, precipitation: h.precipitation});
                                    }
                                });
                                return {
                                    city: name,
                                    days: Object.entries(grouped).map(([date, hours]) => ({
                                        date,
                                        dateFormatted: new Date(date).toLocaleDateString(),
                                        hours
                                    }))
                                };
                            });
                    });
            }))
            .then(results => {
                this.forecast = results.filter(Boolean);
            })
            .catch(err => {
                this.error = err.message || 'Failed to load weather data.';
            });
        }
    }
}
</script>
