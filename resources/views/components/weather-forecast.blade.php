{{-- Weather Forecast Component --}}
@props(['itinerary'])

@php
    $location = optional($itinerary->activities->first())->location;
    $start = $itinerary->start_date->toDateString();
    $end = $itinerary->end_date->toDateString();
@endphp

<div x-data="weatherForecast('{{$start}}','{{$end}}','{{$location}}')" class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6">
    <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Weather Forecast</h3>
    <template x-if="error">
        <p class="text-sm text-red-600" x-text="error"></p>
    </template>
    <template x-if="!error && forecast.length === 0">
        <p class="text-sm text-gray-500 dark:text-gray-400">No rainy hours forecasted.</p>
    </template>
    <div class="grid md:grid-cols-2 gap-4" x-show="forecast.length">
        <template x-for="day in forecast" :key="day.date">
            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded">
                <h4 class="font-medium text-gray-700 dark:text-gray-200 mb-2" x-text="day.dateFormatted"></h4>
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

<script>
function weatherForecast(startDate, endDate, location){
    return {
        error: '',
        forecast: [],
        init(){
            if(!location){
                this.error = 'No location available for weather forecast.';
                return;
            }
            fetch(`https://geocoding-api.open-meteo.com/v1/search?name=${encodeURIComponent(location)}&count=1`)
                .then(r => r.json())
                .then(data => {
                    if(!data.results || !data.results.length){
                        throw new Error('Location not found.');
                    }
                    const { latitude, longitude, timezone } = data.results[0];
                    const url = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&hourly=precipitation_probability,weathercode&start_date=${startDate}&end_date=${endDate}&timezone=${encodeURIComponent(timezone)}`;
                    return fetch(url); 
                })
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
                    this.forecast = Object.entries(grouped).map(([date, hours]) => ({
                        date,
                        dateFormatted: new Date(date).toLocaleDateString(),
                        hours
                    }));
                })
                .catch(err => {
                    this.error = err.message || 'Failed to load weather data.';
                });
        }
    }
}
</script>
