$(document).ready(() => {
    let statusCanvas = $('#chart-node-status');

    if (!statusCanvas.length) {
        return false;
    }

    let up = statusCanvas.data('status-up');
    let down = statusCanvas.data('status-down');

    let colors = [
        'rgb(75, 200, 192)',
        'rgb(255, 99, 132)',
    ];

    let data = [up, down];
    let labels = ['Hours available', 'Hours not available'];

    if (down > up) {
        colors = colors.reverse();
        data = data.reverse();
        labels = labels.reverse();
    }

    new Chart(statusCanvas, {
        type: 'pie',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors,
            }],
        },
        options: {
            legend: {
                display: false,
            },
        },
    });
});
