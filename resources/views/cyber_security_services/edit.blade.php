@extends('template.app')

@section('title', 'Edit Cyber Security Service')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">Edit Cyber Security Service</h4>

        <form id="form">
            @csrf
            @method('PUT')

            <input name="name" class="form-control mb-3" value="{{ $cyberSecurityService->name }}" required>
            <input name="short_name" class="form-control mb-3" value="{{ $cyberSecurityService->short_name }}">

            <select name="category" class="form-select mb-3">
                @foreach (['soc', 'pentest', 'audit', 'incident_response', 'cloud_security', 'governance', 'training', 'consulting'] as $c)
                    <option value="{{ $c }}" @selected($cyberSecurityService->category === $c)>
                        {{ strtoupper(str_replace('_', ' ', $c)) }}
                    </option>
                @endforeach
            </select>

            <textarea name="summary" class="form-control mb-3">{{ $cyberSecurityService->summary }}</textarea>
            <textarea name="description" class="form-control mb-3" rows="4">{{ $cyberSecurityService->description }}</textarea>

            <hr>

            <textarea name="service_scope" class="form-control mb-2">
{{ implode(',', $cyberSecurityService->service_scope ?? []) }}
</textarea>

            <textarea name="deliverables" class="form-control mb-2">
{{ implode(',', $cyberSecurityService->deliverables ?? []) }}
</textarea>

            <textarea name="target_audience" class="form-control mb-2">
{{ implode(',', $cyberSecurityService->target_audience ?? []) }}
</textarea>

            <hr>

            <textarea name="ai_keywords" class="form-control mb-2">
{{ implode(',', $cyberSecurityService->ai_keywords ?? []) }}
</textarea>

            <input name="cta_label" class="form-control mb-2" value="{{ $cyberSecurityService->cta_label }}">
            <input name="cta_url" class="form-control mb-3" value="{{ $cyberSecurityService->cta_url }}">

            <button type="button" class="btn btn-primary" onclick="update()">Update</button>

        </form>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            timer: 3000,
            showConfirmButton: false
        })

        const update = async () => {
            const f = document.getElementById('form')
            const d = new FormData(f)

            ;
            ['service_scope', 'deliverables', 'target_audience', 'ai_keywords']
            .forEach(k => {
                if (f[k]) {
                    d.set(k, JSON.stringify(
                        (f[k].value || '').split(',').map(v => v.trim()).filter(Boolean)
                    ))
                }
            })

            try {
                Swal.showLoading()
                await axios.post('/api/cyber-security-services/{{ $cyberSecurityService->id }}', d)
                Swal.close()
                Toast.fire({
                    icon: 'success',
                    title: 'Service diperbarui'
                })
                setTimeout(() => location.href = '/cyber-security-services', 1200)
            } catch (e) {
                Swal.close()
                Swal.fire('Error', 'Gagal update service', 'error')
            }
        }
    </script>
@endpush
