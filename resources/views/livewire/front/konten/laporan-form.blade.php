<div>
    @auth
        <livewire:user.create-laporan />
    @else
        <div class="text-center">
            <a href="{{ route('login') }}" class="btn btn-primary">Login Untuk Membuat Laporan</a>
        </div>
    @endauth
</div>