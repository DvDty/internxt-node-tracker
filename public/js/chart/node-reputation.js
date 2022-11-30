$(document).ready(() => {
    let reputationCanvas = $('#chart-node-reputation');

    if (!reputationCanvas.length) {
        return false;
    }

    new Chart(reputationCanvas, {
        type: 'line',
        data: {
            labels: reputationCanvas.data('reputation-dates').split(','),
            datasets: [{
                data: reputationCanvas.data('reputation-values').split(','),
                borderColor: 'rgba(52, 58, 64, 1)',
                fill: false,
                'lineTension': 0.1,
            }],
        },
        options: {
            legend: {
                display: false,
            },
        },
    });
});
