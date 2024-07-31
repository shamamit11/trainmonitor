<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form action="{{ route('dashboard') }}" method="GET" class="bg-white p-6 rounded shadow-md">
                    <div class="flex items-center">
                        <div class="mr-4">
                            <label for="station" class="block text-sm font-medium text-gray-700">Select Train
                                Station</label>
                            <select id="station" name="station"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="">Select Station</option>
                                @foreach ($stations as $item)
                                    <option value="{{ $item['code'] }}"
                                        {{ @$station == $item['code'] ? 'selected' : '' }}>
                                        {{ $item['namen']['lang'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mr-4">
                            <label for="type" class="block text-sm font-medium text-gray-700">Select Arrival or
                                Departure</label>
                            <select id="type" name="type"
                                class="mt-1 block w-full px-3 py-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="arrivals" {{ @$type == 'arrivals' ? 'selected' : '' }}>Arrivals
                                </option>
                                <option value="departures" {{ @$type == 'departures' ? 'selected' : '' }}>
                                    Departures</option>
                            </select>
                        </div>
                        <div class="mt-5">
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Fetch Data
                            </button>
                        </div>
                    </div>
                </form>

                @if (isset($disruptions) && count($disruptions) > 0)
                    <div class="bg-red-100 p-6 rounded shadow-md w-full mt-0">
                        <h2 class="text-2xl font-bold mb-4 text-red-700">Disruptions</h2>
                        <ul class="list-disc pl-5">
                            @foreach ($disruptions as $disruption)
                                <li class="text-red-700">{{ $disruption['title'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if (isset($schedules) && count($schedules) > 0)
                    <div class="bg-white p-6 rounded shadow-md w-full">
                        <h2 class="text-2xl font-bold mb-4">Train {{ ucfirst($type) }}</h2>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Train</th>
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Time</th>
                                    @if ($type == 'arrivals')
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Origin</th>
                                    @endif

                                    @if ($type == 'departures')
                                        <th scope="col"
                                            class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Direction</th>
                                    @endif
                                    <th scope="col"
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($schedules as $item)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $item['name'] }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $item['plannedDateTime'] }}</td>
                                        @if ($type == 'arrivals')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item['origin'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item['arrivalStatus'] }}</td>
                                        @endif

                                        @if ($type == 'departures')
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ $item['direction'] }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ @$item['departureStatus'] }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="mt-4">
                            {{ $schedules->appends(['station' => request('station'), 'type' => request('type')])->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
