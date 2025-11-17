<div>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    @if (session('message'))
                        <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700">
                            {{ session('message') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700">
                            {{ session('error') }}
                        </div>
                    @endif

                    <h2 class="text-2xl font-bold mb-4">Sertifikat Relawan</h2>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold mb-2">Detail Relawan</h3>
                        <p><span class="font-medium">Nama:</span> {{ $registration->user->name }}</p>
                        <p><span class="font-medium">Acara:</span> {{ $registration->event->title }}</p>
                        <p><span class="font-medium">Total Jam:</span> {{ number_format($registration->hours_contributed, 2) }} jam</p>
                    </div>

                    @if(!$registration->certificate)
                        <button
                            wire:click="generateCertificate"
                            class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 transition"
                        >
                            Generate Sertifikat
                        </button>
                    @else
                        <div class="space-y-4">
                            <div class="text-sm text-gray-600">
                                <p><span class="font-medium">Nomor Sertifikat:</span> {{ $registration->certificate->certificate_number }}</p>
                                <p><span class="font-medium">Tanggal Terbit:</span> {{ $registration->certificate->issue_date->format('d F Y') }}</p>
                            </div>
                            <button
                                wire:click="downloadCertificate"
                                class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition"
                            >
                                Download Sertifikat
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
