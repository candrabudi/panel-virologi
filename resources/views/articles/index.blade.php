@extends('template.app')

@section('title', 'Articles')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4>Articles</h4>
            <a href="/articles/create" class="btn btn-primary">
                Tambah Article
            </a>
        </div>

        <div class="card">
            <div class="card-body p-0">

                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th width="80">Thumb</th>
                            <th>Title</th>
                            <th>Category</th>
                            <th>Tags</th>
                            <th>Status</th>
                            <th>Publish Date</th>
                            <th width="200">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($articles as $article)
                            <tr data-id="{{ $article->id }}">
                                <td>
                                    @if ($article->thumbnail)
                                        <img src="{{ asset('storage/' . $article->thumbnail) }}" class="rounded"
                                            width="60">
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $article->title }}</strong>
                                    <div class="text-muted small">
                                        {{ $article->slug }}
                                    </div>
                                </td>
                                <td>
                                    @foreach ($article->categories as $cat)
                                        <span class="badge bg-secondary">{{ $cat->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($article->tags as $tag)
                                        <span class="badge bg-light text-dark">{{ $tag->name }}</span>
                                    @endforeach
                                </td>
                                <td>
                                    <span class="badge {{ $article->is_published ? 'bg-success' : 'bg-warning' }}">
                                        {{ $article->is_published ? 'Published' : 'Draft' }}
                                    </span>
                                </td>
                                <td>
                                    {{ $article->published_at?->format('d M Y H:i') ?? '-' }}
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-outline-primary btn-toggle">
                                        {{ $article->is_published ? 'Unpublish' : 'Publish' }}
                                    </button>
                                    <a href="/articles/{{ $article->id }}/edit" class="btn btn-sm btn-outline-secondary">
                                        Edit
                                    </a>
                                    <button class="btn btn-sm btn-outline-danger btn-delete">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.querySelectorAll('.btn-toggle').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr')
                axios.post('/articles/' + tr.dataset.id + '/toggle-publish')
                    .then(() => location.reload())
            })
        })

        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                if (!confirm('Hapus article ini?')) return
                const tr = this.closest('tr')
                axios.delete('/articles/' + tr.dataset.id)
                    .then(() => location.reload())
            })
        })
    </script>
@endpush
