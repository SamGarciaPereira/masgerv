import Chart from "chart.js/auto";

let financeChart = null;
let chartData = {
    receita: null,
    despesa: null,
};

const styles = {
    receita: {
        pago: { color: "#22c55e", label: "Recebido (Pago)" },
        pendente: { color: "#facc15", label: "A Receber (Pendente)" },
        atrasado: { color: "#ef4444", label: "Atrasado" },
    },
    despesa: {
        pago: { color: "#22c55e", label: "Pago" },
        pendente: { color: "#facc15", label: "A Pagar (Pendente)" },
        atrasado: { color: "#ef4444", label: "Vencido" },
    },
};


export function initDashboard(labels, receitaData, despesaData) {
    const ctx = document.getElementById("financeChart");

    if (!ctx) return;

    chartData.receita = receitaData;
    chartData.despesa = despesaData;

    const context = ctx.getContext("2d");

    financeChart = new Chart(context, {
        type: "bar",
        data: {
            labels: labels,
            datasets: [
                {
                    label: styles.receita.pago.label,
                    data: receitaData.pago,
                    backgroundColor: styles.receita.pago.color,
                    stack: "Total",
                },
                {
                    label: styles.receita.pendente.label,
                    data: receitaData.pendente,
                    backgroundColor: styles.receita.pendente.color,
                    stack: "Total",
                },
                {
                    label: styles.receita.atrasado.label,
                    data: receitaData.atrasado,
                    backgroundColor: styles.receita.atrasado.color,
                    stack: "Total",
                },
            ],
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: "index", intersect: false },
            plugins: {
                legend: { position: "bottom" },
                tooltip: {
                    callbacks: {
                        label: function (context) {
                            let label = context.dataset.label || "";
                            if (label) {
                                label += ": ";
                            }
                            if (context.parsed.y !== null) {
                                label += new Intl.NumberFormat("pt-BR", {
                                    style: "currency",
                                    currency: "BRL",
                                }).format(context.parsed.y);
                            }
                            return label;
                        },
                    },
                },
            },
            scales: {
                x: { stacked: true, grid: { display: false } },
                y: {
                    stacked: true,
                    beginAtZero: true,
                    grid: { borderDash: [2, 4], color: "#f3f4f6" },
                    ticks: {
                        callback: function (value) {
                            return value.toLocaleString("pt-BR", {
                                style: "currency",
                                currency: "BRL",
                                maximumSignificantDigits: 3,
                            });
                        },
                    },
                },
            },
        },
    });
}

window.updateChart = function (type) {
    if (!financeChart) return;

    const btnReceita = document.getElementById("btnReceita");
    const btnDespesa = document.getElementById("btnDespesa");
    const title = document.getElementById("chartTitle");

    if (type === "receita") {
        financeChart.data.datasets[0].data = chartData.receita.pago;
        financeChart.data.datasets[1].data = chartData.receita.pendente;
        financeChart.data.datasets[2].data = chartData.receita.atrasado;

        financeChart.data.datasets[0].backgroundColor =
            styles.receita.pago.color;
        financeChart.data.datasets[0].label = styles.receita.pago.label;

        financeChart.data.datasets[1].backgroundColor =
            styles.receita.pendente.color;
        financeChart.data.datasets[1].label = styles.receita.pendente.label;

        financeChart.data.datasets[2].backgroundColor =
            styles.receita.atrasado.color;
        financeChart.data.datasets[2].label = styles.receita.atrasado.label;

        btnReceita.className =
            "px-4 py-1.5 text-sm font-medium rounded-md shadow-sm bg-white text-blue-600 transition-all duration-200";
        btnDespesa.className =
            "px-4 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all duration-200";
        title.innerText = "Fluxo Financeiro (Receitas)";
    } else {
        financeChart.data.datasets[0].data = chartData.despesa.pago;
        financeChart.data.datasets[1].data = chartData.despesa.pendente;
        financeChart.data.datasets[2].data = chartData.despesa.atrasado;

        financeChart.data.datasets[0].backgroundColor =
            styles.despesa.pago.color;
        financeChart.data.datasets[0].label = styles.despesa.pago.label;

        financeChart.data.datasets[1].backgroundColor =
            styles.despesa.pendente.color;
        financeChart.data.datasets[1].label = styles.despesa.pendente.label;

        financeChart.data.datasets[2].backgroundColor =
            styles.despesa.atrasado.color;
        financeChart.data.datasets[2].label = styles.despesa.atrasado.label;

        btnDespesa.className =
            "px-4 py-1.5 text-sm font-medium rounded-md shadow-sm bg-white text-red-600 transition-all duration-200";
        btnReceita.className =
            "px-4 py-1.5 text-sm font-medium rounded-md text-gray-500 hover:text-gray-700 transition-all duration-200";
        title.innerText = "Fluxo Financeiro (Despesas)";
    }

    financeChart.update();
};
