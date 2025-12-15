<div class="modal fade" id="product-modal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Produk</h5>
                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <form id="product-form" enctype="multipart/form-data">

                    <input class="form-control mb-2" name="name" placeholder="Nama Produk">
                    <textarea class="form-control mb-2" name="description" placeholder="Deskripsi"></textarea>

                    <textarea id="editor" name="content"></textarea>

                    <input type="file" class="form-control mt-3" name="thumbnail">
                    <input type="file" class="form-control mt-2" name="images[]" multiple>

                    <hr>
                    <input class="form-control mb-2" name="seo_title" placeholder="SEO Title">
                    <textarea class="form-control mb-2" name="seo_description" placeholder="SEO Description"></textarea>
                    <textarea class="form-control" name="seo_keywords" placeholder="SEO Keywords"></textarea>

                </form>
            </div>

            <div class="modal-footer">
                <button class="btn btn-primary" onclick="save()">Simpan</button>
            </div>

        </div>
    </div>
</div>

<script>
    tinymce.init({
        selector: '#editor',
        height: 300,
        menubar: false,
        plugins: 'lists link code',
        toolbar: 'undo redo | bold italic | bullist numlist | link | code'
    })

    const save = async () => {
        tinymce.triggerSave()
        const data = new FormData(document.getElementById('product-form'))
        await axios.post('/api/products', data)
        document.getElementById('product-modal').classList.remove('show')
        loadProducts()
    }
</script>
