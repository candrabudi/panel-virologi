@extends('template.app')

@section('title', 'Tambah Cyber Security Service')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">Tambah Cyber Security Service</h4>

        <form id="form">
            @csrf

            <input name="name" class="form-control mb-3" placeholder="Nama Service" required>
            <input name="short_name" class="form-control mb-3" placeholder="Short Name">

            <select name="category" class="form-select mb-3">
                @foreach (['soc', 'pentest', 'audit', 'incident_response', 'cloud_security', 'governance', 'training', 'consulting'] as $c)
                    <option value="{{ $c }}">{{ strtoupper(str_replace('_', ' ', $c)) }}</option>
                @endforeach
            </select>

            <textarea name="summary" class="form-control mb-3" placeholder="Ringkasan"></textarea>
            <textarea name="description" class="form-control mb-3" rows="4" placeholder="Deskripsi Lengkap"></textarea>

            <hr>

            <textarea name="service_scope" class="form-control mb-2" placeholder="Service Scope (comma separated)"></textarea>
            <textarea name="deliverables" class="form-control mb-2" placeholder="Deliverables"></textarea>
            <textarea name="target_audience" class="form-control mb-2" placeholder="Target Audience"></textarea>

            <hr>

            <textarea name="ai_keywords" class="form-control mb-2" placeholder="AI Keywords"></textarea>

            <input name="cta_label" class="form-control mb-2" value="Hubungi Kami">
            <input name="cta_url" class="form-control mb-3" placeholder="CTA URL">

            <button type="button" class="btn btn-primary" onclick="save()">Simpan</button>

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

        const save = async () => {
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
                await axios.post('/api/cyber-security-services', d)
                Swal.close()
                Toast.fire({
                    icon: 'success',
                    title: 'Service berhasil disimpan'
                })
                setTimeout(() => location.href = '/cyber-security-services', 1200)
            } catch (e) {
                Swal.close()
                Swal.fire('Error', 'Gagal menyimpan service', 'error')
            }
        }
    </script>
@endpush
