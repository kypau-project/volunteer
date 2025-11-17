<x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __("Laporan & Export Data") }}
    </h2>
</x-slot>

<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Statistics Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-lg font-medium text-gray-900">Total Relawan</h3>
                <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ $totalVolunteers }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-lg font-medium text-gray-900">Total Acara</h3>
                <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ $totalEvents }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-lg font-medium text-gray-900">Total Jam Kontribusi</h3>
                <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ number_format($totalHours, 2) }}</p>
            </div>
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-center">
                <h3 class="text-lg font-medium text-gray-900">Total Feedback</h3>
                <p class="mt-1 text-3xl font-semibold text-indigo-600">{{ $totalFeedbacks }}</p>
            </div>
        </div>

        <!-- Export Section -->
        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 bg-white border-b border-gray-200">
                <h3 class="text-xl font-semibold mb-4">Export Data ke Excel/CSV</h3>

                @if (session()->has("error"))
                    <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700" role="alert">
                        <p>{{ session("error") }}</p>
                    </div>
                @endif

                <div class="space-y-6">
                    <!-- Export Relawan -->
                    <div class="border p-4 rounded-lg flex justify-between items-center">
                        <div>
                            <p class="font-semibold">Data Relawan</p>
                            <p class="text-sm text-gray-600">Export semua data relawan terdaftar.</p>
                        </div>
                        <button wire:click="exportVolunteers" class="bg-green-500 hover:bg-green-600 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out">
                            Export Relawan
                        </button>
                    </div>

                    <!-- Export Kehadiran -->
                    <div class="border p-4 rounded-lg">
                        <p class="font-semibold mb-2">Laporan Kehadiran per Acara</p>
                        <div class="flex items-end space-x-4">
                            <div class="flex-grow">
                                <label for="event_select" class="block text-sm font-medium text-gray-700">Pilih Acara</label>
                                <select wire:model="selectedEventId" id="event_select" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="">-- Pilih Acara --</option>
                                    @foreach($events as $event)
                                        <option value="{{ $event->id }}">{{ $event->title }} ({{ $event->start_date->format("d M Y") }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <button wire:click="exportAttendance" class="bg-blue-500 hover:bg-blue-600 text-white font-bold py-2 px-4 rounded-md shadow-md transition duration-150 ease-in-out" @if(!$selectedEventId) disabled @endif>
                                Export Kehadiran
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
