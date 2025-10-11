<?php
error_reporting(0);
header('Content-Type: application/json');

if (isset($_POST['text'])) {
    $text = trim($_POST['text']);

    if (empty($text)) {
        exit;
    }
    $file = "data.json";


    if (file_exists($file)) {
        $data = json_decode(file_get_contents($file), true);
        if (!is_array($data)) $data = [];
    } else {
        $data = [];
    }


    $data[] = ["text" => $text, "waktu" => date("Y-m-d H:i:s")];


    if (file_put_contents($file, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    } else {
    }
}
?>


<section id="contact" class="py-5 bg-success text-white">
    <div class="container text-center">
        <h2 class="fw-bold mb-4">Laporkan Keluhan Anda</h2>
        <p class="mb-5">Suara Anda membantu kami memahami dan menyelesaikan masalah sosial lebih cepat.</p>

        <form id="aduan" class="row g-3 justify-content-center">
            <div class="col-10">
                <textarea class="form-control" id="text" name="text" rows="4" placeholder="Tulis keluhan Anda..." required></textarea>
            </div>
            <div class="col-12">
                <button type="submit" class="btn btn-warning px-5 mt-2" name="submit" id="submit">Kirim</button>
            </div>
        </form>
        <div id="hasil"></div>
    </div>
</section>
<script>
    document.getElementById("aduan").addEventListener("submit", async function(e) {
        e.preventDefault();

        const text = document.getElementById("text").value.trim();
        if (!text) return;

        const hasilDiv = document.getElementById("hasil");
        hasilDiv.innerHTML = "Memproses aduan...";

        try {

            const formData = new FormData();
            formData.append("text", text);

            const response = await fetch("http://127.0.0.1:5000/proses", {
                method: "POST",
                body: formData
            });

            const result = await response.json();

            if (result.error) {
                hasilDiv.innerHTML = `<p class="text-danger">${result.error}</p>`;
            } else {
                if (result.aduan) {

                    let probAduanHtml = '';
                    for (const [label, prob] of Object.entries(result.probabilitas_aduan)) {
                        probAduanHtml += `<p>${label}: ${prob.toFixed(2)}</p>`;
                    }


                    let probKategoriHtml = '';
                    for (const [label, prob] of Object.entries(result.probabilitas_kategori)) {
                        probKategoriHtml += `<p>${label}: ${prob.toFixed(2)}</p>`;
                    }

                    hasilDiv.innerHTML = `
        <div class="mt-4 p-3 border rounded text-dark bg-light">
            <h5>Hasil Klasifikasi:</h5>
            <p><strong>Teks:</strong> ${result.text}</p>
            <p><strong>Aduan:</strong> Ya</p>
            <div><strong>Probabilitas Aduan:</strong>${probAduanHtml}</div>
            <p><strong>Kategori Prediksi:</strong> ${result.kategori}</p>
            <div><strong>Probabilitas Kategori:</strong>${probKategoriHtml}</div>
        </div>
    `;
                } else {
                    let probAduanHtml = '';
                    for (const [label, prob] of Object.entries(result.probabilitas_aduan)) {
                        probAduanHtml += `<p>${label}: ${prob.toFixed(2)}</p>`;
                    }
                    hasilDiv.innerHTML = `
        <div class="mt-4 p-3 border rounded text-dark bg-light">
            <h5>Hasil Klasifikasi:</h5>
            <p><strong>Teks:</strong> ${result.text}</p>
            <p><strong>Aduan:</strong> Bukan</p>
            <div><strong>Probabilitas Aduan:</strong>${probAduanHtml}</div>
        </div>
    `;
                }

            }
        } catch (err) {
            console.error(err);
            hasilDiv.innerHTML = `<p class="text-danger">Terjadi kesalahan saat memproses data.</p>`;
        }
    });
</script>