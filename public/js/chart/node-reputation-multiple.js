$(document).ready(() => {
    let reputationCanvas = $('#chart-node-reputation-multiple');

    if (!reputationCanvas.length) {
        return false;
    }

    let reputations = [];
    let color;

    Object.keys(chartDataJson.reputations).forEach(nodeId => {
        color = getNextColor();

        reputations.push({
            label: chartDataJson.nodeShortIds[nodeId],
            data: chartDataJson.reputations[nodeId],
            borderColor: color,
            backgroundColor: color,
            fill: false,
            lineTension: 0.1,
        });
    });

    new Chart(reputationCanvas, {
        type: 'line',
        data: {
            labels: chartDataJson.dates,
            datasets: reputations
        },
    });
});
