
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width:700px">
        <div class="card-body p-4">
            <h2>Edit Produk</h2>
            <div id="pesan">Memuat data produk...</div>

            <form id="formProduk" hidden>
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Produk</label>
                    <input id="nama" class="form-control" required maxlength="255">
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea id="deskripsi" class="form-control"></textarea>
                </div>

                <div class="mb-3">
                    <label for="harga" class="form-label">Harga</label>
                    <input id="harga" type="number" min="0" step="1" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="stok" class="form-label">Stok</label>
                    <input id="stok" type="number" min="0" step="1" class="form-control" required>
                </div>

                <button type="submit" id="simpan" class="btn btn-warning">
                    Simpan Perubahan
                </button>
                <a href="{{ route('produk.index') }}" class="btn btn-secondary">
                    Batal
                </a>
            </form>

            <a id="kembali" href="{{ route('produk.index') }}" class="btn btn-secondary d-none">
                Kembali
            </a>
        </div>
    </div>
</div>

<script>
const API = '/api/v1/produk';
const ID_PRODUK = @json($id);
const form = document.getElementById('formProduk');
const pesan = document.getElementById('pesan');

function tampilkanPesan(teks, tipe = 'danger') {
    pesan.replaceChildren();
    const alert = document.createElement('div');
    alert.className = `alert alert-${tipe}`;
    alert.textContent = teks;
    pesan.appendChild(alert);
}

// GET /api/v1/produk/{id}
async function ambilProduk() {
    try {
        const response = await fetch(`${API}/${ID_PRODUK}`, {
            headers: { Accept: 'application/json' }
        });

        const hasil = await response.json();

        if (!response.ok) {
            throw new Error(hasil.message || 'Gagal mengambil produk.');
        }

        const produk = hasil.data ?? hasil;

        document.getElementById('nama').value = produk.nama ?? '';
        document.getElementById('deskripsi').value = produk.deskripsi ?? '';
        document.getElementById('harga').value = produk.harga ?? '';
        document.getElementById('stok').value = produk.stok ?? 0;

        pesan.replaceChildren();
        form.hidden = false;
    } catch (error) {
        tampilkanPesan(error.message);
        document.getElementById('kembali').classList.remove('d-none');
    }
}

// PUT /api/v1/produk/{id}
form.addEventListener('submit', async function (event) {
    event.preventDefault();

    const tombol = document.getElementById('simpan');
    tombol.disabled = true;

    const data = {
        nama: document.getElementById('nama').value.trim(),
        deskripsi: document.getElementById('deskripsi').value.trim() || null,
        harga: Number(document.getElementById('harga').value),
        stok: Number(document.getElementById('stok').value)
    };

    try {
        const response = await fetch(`${API}/${ID_PRODUK}`, {
            method: 'PUT',
            headers: {
                Accept: 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const hasil = await response.json();

        if (!response.ok) {
            const errors = hasil.errors
                ? Object.values(hasil.errors).flat().join(' ')
                : hasil.message;

            throw new Error(errors || 'Gagal memperbarui produk.');
        }

        window.location.href = "{{ route('produk.index') }}";
    } catch (error) {
        tampilkanPesan(error.message);
    } finally {
        tombol.disabled = false;
    }
});

ambilProduk();
</script>
</body>
</html>
