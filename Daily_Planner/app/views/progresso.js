let progressChart = null; 

document.addEventListener('DOMContentLoaded', () => {
    fetchProgressData('semanal');
});

async function fetchProgressData(view = 'semanal') {
    try {
        const response = await fetch(`../controllers/get_progresso.php?view=${view}`);
        const data = await response.json();

        console.log('Dados recebidos do servidor:', data); 

        if (data.success && data.progress) {
            const { total, completed, pending, chartData, chartLabels } = data.progress;

            document.getElementById('completed-tasks').textContent = completed;
            document.getElementById('pending-tasks').textContent = pending;
            document.getElementById('total-tasks').textContent = total;

            renderLineChart(chartData, chartLabels);

        } else {
            console.error('O servidor indicou um erro ou retornou dados malformados:', data.error || 'Nenhum detalhe do erro fornecido.');
        }
    } catch (error) {
        console.error('Falha crítica na comunicação ou ao processar a resposta:', error);
    }
}

function renderLineChart(data, labels) {
    const ctx = document.getElementById('progressLineChart').getContext('2d');
    
    if (progressChart) {
        progressChart.destroy();
    }
    
    progressChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tarefas Concluídas',
                data: data,
                borderColor: '#604A3E',
                backgroundColor: 'rgba(96, 74, 62, 0.1)',
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#604A3E',
                pointRadius: 5,
                pointHoverRadius: 7,
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false, 
            plugins: { 
                legend: { display: false } 
            },
            scales: {
                y: { 
                    beginAtZero: true, 
                    ticks: { 
                        stepSize: 1,
                        precision: 0
                    } 
                },
                // LINHA ADICIONADA: Deixa os rótulos do eixo X (semanas/dias) em negrito
                x: {
                    ticks: {
                        font: {
                            weight: 'bold'
                        }
                    }
                }
            }
        }
    });
}

function changeView(view) {
    const tabs = document.querySelectorAll('.tabs-container .tab');
    tabs.forEach(tab => tab.classList.remove('active'));
    
    if (view === 'semanal') {
        tabs[0].classList.add('active');
    } else {
        tabs[1].classList.add('active');
    }
    
    fetchProgressData(view);
}