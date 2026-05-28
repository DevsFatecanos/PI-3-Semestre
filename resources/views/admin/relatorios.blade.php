@extends('layouts.app')

@section('content')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        background-color: #ffffff;
    }

    .dashboard-chart-card {
        min-height: 280px;
    }

    .dashboard-chart-shell {
        position: relative;
        height: 220px;
    }

    .dashboard-chart-shell.is-compact {
        height: 190px;
    }

    .dashboard-chart-shell.is-tall {
        height: 240px;
    }

    .dashboard-chart-shell canvas {
        width: 100% !important;
        height: 100% !important;
    }

    @media (max-width: 768px) {
        .dashboard-chart-shell,
        .dashboard-chart-shell.is-compact,
        .dashboard-chart-shell.is-tall {
            height: 180px;
        }
    }
</style>
<script id="#userwayAccessibilityIcon" src = " https://cdn.userway.org/widget.js "  data-account = " wHedvuvp49 " ></script>
    <style>
    #userwayAccessibilityIcon {
        margin-top: 500px;
    }
    </style>

<div class="container mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3">
                <i class="fas fa-chart-bar"></i> Relatórios e Gráficos
            </h1>
        </div>
    </div>

    <!-- Ações de exportação -->
    <div class="mb-4">
        <div class="card p-4">
            <h5 class="mb-3">
                <i class="fas fa-download"></i> Exportar e Imprimir Dados
            </h5>
            <div class="row g-2">
                <div class="col-md-6">
                    <h6 class="mb-2">Vendas</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.dashboard.export', ['type' => 'sales']) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-file-csv"></i> Exportar (CSV)
                        </a>
                        <a href="{{ route('admin.dashboard.print', ['type' => 'sales']) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-print"></i> Imprimir Relatório
                        </a>
                    </div>
                </div>
                <div class="col-md-6">
                    <h6 class="mb-2">Produtos</h6>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('admin.dashboard.export', ['type' => 'products']) }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-file-csv"></i> Exportar (CSV)
                        </a>
                        <a href="{{ route('admin.dashboard.print', ['type' => 'products']) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-print"></i> Imprimir Relatório
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card p-3 dashboard-chart-card">
                <h5>
                    <i class="fas fa-cubes"></i> Distribuição de Estoque
                </h5>
                <div class="dashboard-chart-shell is-compact">
                    <canvas id="stockChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card p-3 dashboard-chart-card">
                <h5>
                    <i class="fas fa-tag"></i> Top Categorias
                </h5>
                <div class="dashboard-chart-shell is-compact">
                    <canvas id="categoriesChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card p-3 dashboard-chart-card">
                <h5>
                    <i class="fas fa-star"></i> Top Produtos
                </h5>
                <div class="dashboard-chart-shell is-tall">
                    <canvas id="produtosChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const initialPayload = @json($chartData ?? []);
    const dashboardDataUrl = @json(route('admin.dashboard.data'));
    const chartPalette = ['#e74c3c', '#f1c40f', '#3498db', '#2ecc71', '#9b59b6', '#1abc9c'];

    let stockChart = null;
    let categoriesChart = null;
    let produtosChart = null;

    const normalizeChartData = (payload) => payload?.chartData ?? payload ?? {};

    const ensureChart = (chart, ctx, config) => {
        if (chart) {
            chart.data = config.data;
            chart.options = config.options;
            chart.update();
            return chart;
        }

        return new Chart(ctx, config);
    };

    const renderCharts = (payload) => {
        const chartData = normalizeChartData(payload);

        const stockCanvas = document.getElementById('stockChart');
        const categoriesCanvas = document.getElementById('categoriesChart');
        const produtosCanvas = document.getElementById('produtosChart');

        if (!stockCanvas || !categoriesCanvas || !produtosCanvas) {
            return;
        }

        const stockLabels = Object.keys(chartData.stockDistribution || {});
        const stockValues = Object.values(chartData.stockDistribution || {});

        stockChart = ensureChart(stockChart, stockCanvas.getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: stockLabels,
                datasets: [{
                    data: stockValues,
                    backgroundColor: chartPalette,
                    borderWidth: 0,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '62%',
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                },
            },
        });

        const categories = (chartData.topCategories || []).map((item) => item.categoria || 'N/A');
        const catValues = (chartData.topCategories || []).map((item) => Number(item.total_qtd) || 0);

        categoriesChart = ensureChart(categoriesChart, categoriesCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: categories,
                datasets: [{
                    label: 'Quantidade vendida',
                    data: catValues,
                    backgroundColor: '#3498db',
                    borderRadius: 8,
                    maxBarThickness: 28,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });

        const produtos = (chartData.topProdutos || []).map((item) => item.nome || 'N/A');
        const prodValues = (chartData.topProdutos || []).map((item) => Number(item.total_qtd) || 0);

        produtosChart = ensureChart(produtosChart, produtosCanvas.getContext('2d'), {
            type: 'bar',
            data: {
                labels: produtos,
                datasets: [{
                    label: 'Quantidade vendida',
                    data: prodValues,
                    backgroundColor: '#2ecc71',
                    borderRadius: 8,
                    maxBarThickness: 20,
                }],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y',
                plugins: {
                    legend: {
                        display: false,
                    },
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                        },
                    },
                },
            },
        });
    };

    const refreshCharts = async () => {
        try {
            const response = await fetch(dashboardDataUrl, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                cache: 'no-store',
            });

            if (!response.ok) {
                return;
            }

            const payload = await response.json();
            renderCharts(payload);
        } catch (error) {
            console.warn('Erro ao atualizar gráficos dos relatórios', error);
        }
    };

    try {
        renderCharts(initialPayload);
        refreshCharts();
        setInterval(refreshCharts, 30000);
    } catch (error) {
        console.warn('Erro ao renderizar gráficos dos relatórios', error);
    }
</script>
@endpush
