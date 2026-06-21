@extends('layouts.public')

@section('title', 'Statistik Penduduk ' . \App\Models\Setting::get('nama_desa', 'Desa Penebal') . ' - Portal Resmi')

@section('content')
    <section class="section" style="padding-top: 48px;">
        <div class="container">
            <div class="section-header" style="margin-bottom: 40px; text-align: left; max-width: 100%;">
                <h2 style="font-size: 32px; color: var(--secondary-color);">Statistik Penduduk</h2>
                <p>Visualisasi data kependudukan {{ \App\Models\Setting::get('nama_desa', 'Desa Penebal') }} secara langsung berdasarkan data riil basis data kependudukan desa.</p>
            </div>

            <!-- Summary Bar -->
            <div class="card" style="display: flex; justify-content: space-between; align-items: center; padding: 20px 32px; margin-bottom: 40px; background: linear-gradient(135deg, var(--secondary-color), #1e293b); color: white;">
                <div>
                    <h4 style="color: white; font-size: 15px; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 4px;">Total Penduduk Terdaftar</h4>
                    <span style="font-size: 13px; color: var(--text-light);">Update otomatis dari server kependudukan desa</span>
                </div>
                <div style="font-size: 40px; font-family: var(--font-heading); font-weight: 800; color: #60a5fa;">
                    {{ $totalPenduduk }} <span style="font-size: 18px; font-weight: 500; color: white;">Jiwa</span>
                </div>
            </div>

            <!-- Charts Grid -->
            <div class="charts-grid">
                <!-- 1. Gender Chart -->
                <div class="chart-container">
                    <h3 class="chart-title">Distribusi Jenis Kelamin</h3>
                    <div style="height: 280px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="chartGender"></canvas>
                    </div>
                </div>

                <!-- 2. Age Groups Chart -->
                <div class="chart-container">
                    <h3 class="chart-title">Kelompok Usia Penduduk</h3>
                    <div style="height: 280px;">
                        <canvas id="chartAge"></canvas>
                    </div>
                </div>

                <!-- 3. Religion Chart -->
                <div class="chart-container">
                    <h3 class="chart-title">Keyakinan / Agama</h3>
                    <div style="height: 280px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="chartReligion"></canvas>
                    </div>
                </div>

                <!-- 4. Education Chart -->
                <div class="chart-container">
                    <h3 class="chart-title">Tingkat Pendidikan Terakhir</h3>
                    <div style="height: 280px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="chartEducation"></canvas>
                    </div>
                </div>

                <!-- 5. Occupation Chart (Wide) -->
                <div class="chart-container chart-container-wide">
                    <h3 class="chart-title">Mata Pencaharian Penduduk</h3>
                    <div style="height: 300px;">
                        <canvas id="chartOccupation"></canvas>
                    </div>
                </div>

                <!-- 6. Bansos Chart -->
                <div class="chart-container">
                    <h3 class="chart-title">Penerima Bantuan Sosial</h3>
                    <div style="height: 280px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="chartBansos"></canvas>
                    </div>
                </div>

                <!-- 7. UMKM Chart -->
                <div class="chart-container">
                    <h3 class="chart-title">Pelaku UMKM vs Umum</h3>
                    <div style="height: 280px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="chartUmkm"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script>
    // Palet Warna Premium
    const colors = {
        blue: '#2563eb',
        teal: '#10b981',
        indigo: '#6366f1',
        amber: '#f59e0b',
        rose: '#f43f5e',
        violet: '#8b5cf6',
        slate: '#64748b'
    };

    // Helper untuk grafik Pie / Doughnut
    function createPieChart(elementId, labels, data, palette) {
        new Chart(document.getElementById(elementId), {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: data,
                    backgroundColor: palette,
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { family: 'Plus Jakarta Sans', size: 12 },
                            usePointStyle: true,
                            padding: 16
                        }
                    }
                },
                cutout: '65%'
            }
        });
    }

    // 1. Gender Chart
    const genderLabels = {!! json_encode(array_keys($genderData)) !!};
    const genderVal = {!! json_encode(array_values($genderData)) !!};
    createPieChart('chartGender', genderLabels, genderVal, [colors.blue, colors.rose]);

    // 2. Age Groups Chart
    const ageLabels = {!! json_encode(array_keys($ageGroups)) !!};
    const ageVal = {!! json_encode(array_values($ageGroups)) !!};
    new Chart(document.getElementById('chartAge'), {
        type: 'bar',
        data: {
            labels: ageLabels,
            datasets: [{
                label: 'Jumlah Jiwa',
                data: ageVal,
                backgroundColor: colors.indigo,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                x: { grid: { display: false } }
            }
        }
    });

    // 3. Religion Chart
    const relLabels = {!! json_encode(array_keys($religionData)) !!};
    const relVal = {!! json_encode(array_values($religionData)) !!};
    createPieChart('chartReligion', relLabels, relVal, [colors.blue, colors.teal, colors.indigo, colors.amber, colors.violet]);

    // 4. Education Chart
    const eduLabels = {!! json_encode(array_keys($educationData)) !!};
    const eduVal = {!! json_encode(array_values($educationData)) !!};
    createPieChart('chartEducation', eduLabels, eduVal, [colors.blue, colors.teal, colors.indigo, colors.amber, colors.rose, colors.slate]);

    // 5. Occupation Chart
    const occLabels = {!! json_encode(array_keys($occupationData)) !!};
    const occVal = {!! json_encode(array_values($occupationData)) !!};
    new Chart(document.getElementById('chartOccupation'), {
        type: 'bar',
        data: {
            labels: occLabels,
            datasets: [{
                label: 'Jumlah Jiwa',
                data: occVal,
                backgroundColor: colors.teal,
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: 'y', // Horizontal bar chart
            plugins: { legend: { display: false } },
            scales: {
                x: { beginAtZero: true, grid: { color: '#f1f5f9' } },
                y: { grid: { display: false } }
            }
        }
    });

    // 6. Bansos Chart
    const bansosLabels = {!! json_encode(array_keys($bansosData)) !!};
    const bansosVal = {!! json_encode(array_values($bansosData)) !!};
    createPieChart('chartBansos', bansosLabels, bansosVal, [colors.blue, colors.violet, colors.rose, colors.slate]);

    // 7. UMKM Chart
    const umkmLabels = {!! json_encode(array_keys($umkmData)) !!};
    const umkmVal = {!! json_encode(array_values($umkmData)) !!};
    createPieChart('chartUmkm', umkmLabels, umkmVal, [colors.slate, colors.amber]);
</script>
@endsection
