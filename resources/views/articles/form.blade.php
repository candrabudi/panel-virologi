@extends('template.app')

@section('title', $article ? 'Edit Article' : 'Create Article')

@section('content')
    <div class="container-fluid">

        <h4 class="mb-4">{{ $article ? 'Edit Article' : 'Create Article' }}</h4>

        <form id="form" enctype="multipart/form-data">
            @csrf

            <div class="row g-4">

                <div class="col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">
                            <input name="title" class="form-control mb-3" placeholder="Title"
                                value="{{ $article->title ?? '' }}">

                            <textarea name="excerpt" class="form-control mb-3" placeholder="Excerpt" rows="2">{{ $article->excerpt ?? '' }}</textarea>

                            <textarea name="content" class="form-control" placeholder="Content" rows="10">{{ $article->content ?? '' }}</textarea>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <h6 class="mb-3">SEO</h6>

                            <input name="seo_title" class="form-control mb-2" placeholder="SEO Title"
                                value="{{ $article->seo_title ?? '' }}">

                            <textarea name="seo_description" class="form-control mb-2" placeholder="SEO Description" rows="2">{{ $article->seo_description ?? '' }}</textarea>

                            <input name="seo_keywords" class="form-control mb-2" placeholder="SEO Keywords"
                                value="{{ $article->seo_keywords ?? '' }}">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <label class="form-label">Thumbnail</label>
                            <input type="file" name="thumbnail" class="form-control mb-2">

                            @if ($article?->thumbnail)
                                <img src="{{ asset('storage/' . $article->thumbnail) }}" class="img-fluid rounded">
                            @endif
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <label class="form-label">Categories</label>
                            @foreach ($categories as $cat)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="categories[]"
                                        value="{{ $cat->id }}" @checked($article && $article->categories->contains($cat->id))>
                                    <label class="form-check-label">
                                        {{ $cat->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card mb-4">
                        <div class="card-body">
                            <label class="form-label">Tags</label>
                            @foreach ($tags as $tag)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="tags[]"
                                        value="{{ $tag->id }}" @checked($article && $article->tags->contains($tag->id))>
                                    <label class="form-check-label">
                                        {{ $tag->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body">
                            <div class="form-check mb-3">
                                <input type="checkbox" name="is_published" class="form-check-input" value="1"
                                    @checked($article?->is_published)>
                                <label class="form-check-label">
                                    Publish
                                </label>
                            </div>

                            <button class="btn btn-primary w-100">
                                Simpan Article
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('form').addEventListener('submit', function(e) {
            e.preventDefault()
            const url = '{{ $article ? '/articles/' . $article->id : '/articles' }}'
            axios.post(url, new FormData(this))
                .then(res => {
                    if (res.data.redirect) {
                        window.location.href = res.data.redirect
                    }
                })
        })
    </script>
@endpush
