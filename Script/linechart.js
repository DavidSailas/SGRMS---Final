document.addEventListener("DOMContentLoaded", function () {
    const ctx = document.getElementById('caseChart').getContext('2d');

    // Generate a color for each case type
    const colors = [
        'rgba(54, 162, 235, 1)',
        'rgba(255, 99, 132, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)'
    ];

    const datasets = Object.keys(caseTypeData).map((type, idx) => ({
        label: type,
        data: Object.values(caseTypeData[type]),
        borderColor: colors[idx % colors.length],
        backgroundColor: colors[idx % colors.length].replace('1)', '0.2)'),
        tension: 0.4,
        fill: false,
        pointBackgroundColor: '#fff',
        pointBorderWidth: 2,
        pointRadius: 4
    }));

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: [
                'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun',
                'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
            ],
            datasets: datasets
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        color: '#333',
                        boxWidth: 12,
                        padding: 15,
                        font: {
                            size: 13,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    mode: 'index',
                    intersect: false,
                    backgroundColor: '#222',
                    titleColor: '#fff',
                    bodyColor: '#fff',
                    borderColor: '#555',
                    borderWidth: 1,
                    cornerRadius: 6,
                    padding: 10
                },
                title: {
                    display: false
                }
            },
            interaction: {
                mode: 'nearest',
                axis: 'x',
                intersect: false
            },
            scales: {
                x: {
                    ticks: {
                        color: '#333',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                },
                y: {
                    ticks: {
                        color: '#333',
                        font: {
                            size: 12
                        },
                        beginAtZero: true
                    },
                    grid: {
                        color: 'rgba(200, 200, 200, 0.2)',
                        borderDash: [5, 5]
                    }
                }
            }
        }
    });
});
