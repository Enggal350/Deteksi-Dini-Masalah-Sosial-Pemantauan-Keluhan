// Custom JS untuk interaktivitas dan grafik

// Inisialisasi Grafik Chart.js (Data dummy dari Label Studio)
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('keluhanChart').getContext('2d');
    const keluhanChart = new Chart(ctx, {
        type: 'pie', // Bisa diganti ke 'bar' jika mau
        data: {
            labels: ['Kemiskinan', 'Kekerasan', 'Lingkungan', 'Kesehatan'],
            datasets: [{
                data: [40, 30, 20, 10], // Data dummy: Persentase dari dataset Label Studio
                backgroundColor: [
                    '#ff6384', // Merah untuk kemiskinan
                    '#36a2eb', // Biru untuk kekerasan
                    '#ffce56', // Kuning untuk lingkungan
                    '#4bc0c0'  // Cyan untuk kesehatan
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 20,
                        font: { size: 14 }
                    }
                },
                title: {
                    display: true,
                    text: 'Distribusi Label Keluhan Sosial (%)',
                    font: { size: 16 }
                }
            }
        }
    });

    // Smooth scroll untuk navbar links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Form submit handler (sederhana, bisa diintegrasikan ke backend nanti)
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        alert('Keluhan Anda telah disubmit! Terima kasih telah berkontribusi.');
        form.reset();
    });
});

    AOS.init();

    // === PIE CHART ===
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    const pieChart = new Chart(pieCtx, {
      type: 'doughnut',
      data: {
        labels: ['Pelayanan Publik', 'Infrastruktur', 'Sosial Ekonomi', 'Pendidikan', 'Ketertiban', 'Lingkungan'],
        datasets: [{
          data: [254, 228, 115, 56, 54, 43],
          backgroundColor: ['#28a745', '#ffc107', '#17a2b8', '#dc3545', '#6f42c1', '#ff5722'],
          borderWidth: 2,
          borderColor: '#fff'
        }]
      },
      options: {
        cutout: '10%',
        plugins: {
          legend: { position: 'bottom' }
        }
      }
    });

    // === LINE CHART DENGAN ANIMASI ===
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    const lineChart = new Chart(lineCtx, {
      type: 'line',
      data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt'],
        datasets: [{
          label: 'Jumlah Keluhan',
          data: [30, 45, 60, 80, 100, 95, 120, 130, 140, 150],
          borderColor: '#28a745',
          backgroundColor: 'rgba(40,167,69,0.2)',
          tension: 0.4,
          fill: true
        }]
      },
      options: {
        animation: { duration: 800 },
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { display: false } }
      }
    });

    // === DATA REALTIME ===
    const contohKeluhan = [
      "Jalan rusak di Desa Kalimanah.",
      "Air PDAM sering mati di sore hari.",
      "Sampah menumpuk di Pasar Bobotsari.",
      "Pelayanan administrasi lambat.",
      "Lampu jalan padam di perempatan.",
      "Sekolah butuh buku pelajaran baru."
    ];
    const feed = document.getElementById('keluhanFeed');
    const keluhanCounter = document.getElementById('jumlahKeluhan');
    let totalKeluhan = 750;

    setInterval(() => {
      // Tambahkan keluhan baru
      const randomKeluhan = contohKeluhan[Math.floor(Math.random() * contohKeluhan.length)];
      const li = document.createElement('li');
      li.className = 'list-group-item';
      li.textContent = randomKeluhan + " (" + new Date().toLocaleTimeString() + ")";
      feed.prepend(li);
      if (feed.children.length > 6) feed.removeChild(feed.lastChild);

      // Tambahkan total keluhan
      const tambahan = Math.floor(Math.random() * 5) + 1;
      totalKeluhan += tambahan;
      keluhanCounter.innerText = totalKeluhan;
      keluhanCounter.classList.add('pulse');
      setTimeout(() => keluhanCounter.classList.remove('pulse'), 600);

      // Update line chart (naik-turun)
      const newData = lineChart.data.datasets[0].data;
      const newValue = newData[newData.length - 1] + (Math.random() * 10 - 5);
      newData.push(Math.max(0, newValue));
      if (newData.length > 12) newData.shift();

      const newLabel = new Date().toLocaleTimeString();
      lineChart.data.labels.push(newLabel);
      if (lineChart.data.labels.length > 12) lineChart.data.labels.shift();

      lineChart.update();
    }, 4000);

// Jika Anda punya dataset dari Label Studio (misalnya via fetch JSON), ganti data di atas dengan:
// fetch('path/to/your/labelstudio.json').then(res => res.json()).then(data => {
//     // Proses data dan update chart
//     keluhanChart.data.labels = data.labels;
//     keluhanChart.data.datasets[0].data = data.values;
//     keluhanChart.update();
// });