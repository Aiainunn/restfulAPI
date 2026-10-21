<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Tambah Produk</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

<div class="container py-5">
    <div class="card border-0 shadow-sm mx-auto" style="max-width: 700px">
        <div class="card-body p-4">

            <h2 class="mb-4">Tambah Produk</h2>

            <!-- Pesan status dan validasi -->
            <div id="pesan" role="alert"></div>

            <form id="formProduk">

                <div class="mb-3">
                    <label class="form-label" for="nama">
                        Nama Produk
                    </label>
                    <input
                        type="text"
                        class="form-control"
                        id="nama"
                        maxlength="255"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label" for="deskripsi">
                        Deskripsi
                    </label>
                    <textarea
                        class="form-control"
                        id="deskripsi"
                        rows="3"
                    ></textarea>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="harga">
                        Harga
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="harga"
                        min="0"
                        step="1"
                        required
                    >
                </div>

                <div class="mb-3">
                    <label class="form-label" for="stok">
                        Stok
                    </label>
                    <input
                        type="number"
                        class="form-control"
                        id="stok"
                        min="0"
                        step="1"
                        value="0"
                        required
                    >
                </div>

                <button
                    class="btn btn-primary"
                    id="simpan"
                    type="submit"
                >
                    Simpan Produk
                </button>

                <a
                    href="{{ route('produk.index') }}"
                    class="btn btn-secondary"
                >
                    Kembali
                </a>

            </form>
        </div>
    </div>
</div>

<script>
    // Endpoint API tanpa prefix v1
    const API = '/api/produk';

    const form = document.getElementById('formProduk');
    const pesan = document.getElementById('pesan');
    const tombol = document.getElementById('simpan');

    function tampilkanPesan(teks, jenis = 'danger') {
        pesan.replaceChildren();

        const alert = document.createElement('div');
        alert.className = `alert alert-${jenis}`;
        alert.textContent = teks;

        pesan.appendChild(alert);
    }

    form.addEventListener('submit', async function (event) {
        // Mencegah form terkirim secara langsung ke endpoint API
        event.preventDefault();

        tombol.disabled = true;
        tombol.textContent = 'Menyimpan...';

        const data = {
            nama: document.getElementById('nama').value.trim(),
            deskripsi:
                document.getElementById('deskripsi').value.trim() || null,
            harga: Number(document.getElementById('harga').value),
            stok: Number(document.getElementById('stok').value)
        };

        try {
            const response = await fetch(API, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            const hasil = await response.json();

            if (!response.ok) {
                const errorValidasi = hasil.errors
                    ? Object.values(hasil.errors).flat().join(' ')
                    : hasil.message;

                throw new Error(
                    errorValidasi || 'Gagal menambahkan produk.'
                );
            }

            tampilkanPesan(
                'Produk berhasil ditambahkan. Mengalihkan halaman...',
                'success'
            );

            // Kembali ke halaman web, bukan halaman JSON API
            window.location.href = @json(route('produk.index'));

        } catch (error) {
            tampilkanPesan(
                error.message || 'Terjadi kesalahan saat menyimpan produk.'
            );

            tombol.disabled = false;
            tombol.textContent = 'Simpan Produk';
        }
    });
</script>

</body>
</html>