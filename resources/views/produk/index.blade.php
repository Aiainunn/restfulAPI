
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2>Daftar Produk</h2>
            <p class="text-muted">Data diambil dari RESTful API menggunakan JSON.</p>
        </div>
        <a href="{{ route('produk.create') }}" class="btn btn-primary">+ Tambah Produk</a>
    </div>

    <div id="pesan"></div>

    <div class="card shadow-sm border-0">
        <div class="card-body table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>Nama</th>
                        <th>Deskripsi</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="tabelProduk">
                    <tr><td colspan="6">Memuat data...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
const API = '/api/v1/produk';
const tabel = document.getElementById('tabelProduk');
const pesan = document.getElementById('pesan');

async function requestJSON(url, options = {}) {
    const response = await fetch(url, {
        ...options,
        headers: {
            Accept: 'application/json',
            'Content-Type': 'application/json',
            ...options.headers
        }
    });

    const result = response.status === 204
        ? null
        : await response.json();

    if (!response.ok) {
        throw new Error(result?.message || 'Permintaan API gagal.');
    }

    return result;
}

function tampilkanPesan(teks, tipe = 'success') {
    pesan.innerHTML = '';
    const alert = document.createElement('div');
    alert.className = `alert alert-${tipe}`;
    alert.textContent = teks;
    pesan.appendChild(alert);
}

function rupiah(nilai) {
    return 'Rp ' + Number(nilai).toLocaleString('id-ID');
}

// GET /api/v1/produk
async function tampilkanProduk() {
    try {
        const hasil = await requestJSON(API);
        const daftar = Array.isArray(hasil)
            ? hasil
            : (hasil?.data ?? []);

        tabel.replaceChildren();

        if (!daftar.length) {
            tabel.innerHTML = '<tr><td colspan="6" class="text-center">Belum ada produk.</td></tr>';
            return;
        }

        daftar.forEach((produk, index) => {
            const tr = document.createElement('tr');

            const nilai = [
                index + 1,
                produk.nama ?? '-',
                produk.deskripsi || '-',
                rupiah(produk.harga),
                produk.stok ?? 0
            ];

            nilai.forEach(teks => {
                const td = document.createElement('td');
                td.textContent = teks;
                tr.appendChild(td);
            });

            const aksi = document.createElement('td');

            const detail = document.createElement('a');
            detail.href = `/produk/${produk.id}`;
            detail.className = 'btn btn-info btn-sm me-1';
            detail.textContent = 'Detail';

            const edit = document.createElement('a');
            edit.href = `/produk/${produk.id}/edit`;
            edit.className = 'btn btn-warning btn-sm me-1';
            edit.textContent = 'Edit';

            const hapus = document.createElement('button');
            hapus.className = 'btn btn-danger btn-sm';
            hapus.textContent = 'Hapus';
            hapus.addEventListener('click', () => hapusProduk(produk.id));

            aksi.append(detail, edit, hapus);
            tr.appendChild(aksi);
            tabel.appendChild(tr);
        });
    } catch (error) {
        tabel.innerHTML = '<tr><td colspan="6">Gagal memuat produk.</td></tr>';
        tampilkanPesan(error.message, 'danger');
    }
}

// DELETE /api/v1/produk/{id}
async function hapusProduk(id) {
    if (!confirm('Yakin ingin menghapus produk ini?')) return;

    try {
        const hasil = await requestJSON(`${API}/${id}`, {
            method: 'DELETE'
        });

        tampilkanPesan(hasil?.message || 'Produk berhasil dihapus.');
        await tampilkanProduk();
    } catch (error) {
        tampilkanPesan(error.message, 'danger');
    }
}

tampilkanProduk();
</script>
</body>
</html>
