@push('js')
<script>
    document.addEventListener('livewire:initialized', () => {
        Livewire.on('notify', (data) => {
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            Toast.fire({
                icon: data[0].icon,
                title: data[0].title
            });
        });
    });
</script>
@endpush