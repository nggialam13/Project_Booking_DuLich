@if(session('success'))
<div class="alert alert-success alert-dismissible fade show">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show">
    {{ session('error') }}
</div>
@endif

@if(session('info'))
<div class="alert alert-info alert-dismissible fade show">
    {{ session('info') }}
</div>
@endif

<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => el.remove());
    }, 3000);
</script>
