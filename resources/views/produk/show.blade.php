
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Detail Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width:700px">
        <div class="card-body p-4">
            <h2>Detail Produk</h2>
            <div id="pesan" class="text-muted">Memuat detail produk...</div>

            <dl id="detailProduk" class="mt-4" hidden>
                <dt>ID Produk</dt>
                <dd id="id"></dd>

                <dt>Nama Produk</dt>
                <dd id="nama"></dd>

                <dt>Deskripsi</dt>
                <dd id="deskripsi"></dd>

                <dt>Harga</dt>
                <dd id="harga"></dd>

                <dt>Stok</dt>
                <dd id="stok"></dd>
            </dl>

            <div class="d-flex gap-2">
                <a id="edit" href="#" class="btn btn-warning d-none">Edit</a>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>

<script>
const API = '/api/v1/produk';
const ID_PRODUK = @json($id);

async function tampilkanDetail() {
    const pesan = document.getElementById('pesan');

    try {
        // GET /api/v1/produk/{id}
        const response = await fetch(`${API}/${ID_PRODUK}`, {
            headers: { Accept: 'application/json' }
        });

        const hasil = await response.json();

        if (!response.ok) {
            throw new Error(hasil.message || 'Produk tidak ditemukan.');
        }

        const produk = hasil.data ?? hasil;

        document.getElementById('id').textContent = produk.id;
        document.getElementById('nama').textContent = produk.nama ?? '-';
        document.getElementById('deskripsi').textContent = produk.deskripsi || '-';
        document.getElementById('harga').textContent =
            'Rp ' + Number(produk.harga).toLocaleString('id-ID');
        document.getElementById('stok').textContent = produk.stok ?? 0;

        document.getElementById('detailProduk').hidden = false;

        const edit = document.getElementById('edit');
        edit.href = `/produk/${produk.id}/edit`;
        edit.classList.remove('d-none');

        pesan.textContent = '';
    } catch (error) {
        pesan.className = 'alert alert-danger';
        pesan.textContent = error.message;
    }
}

tampilkanDetail();
</script>
</body>
</html>
