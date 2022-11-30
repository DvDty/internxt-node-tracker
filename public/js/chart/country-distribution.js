let countryDistributionCanvas = $('#chart-country-distribution');

new Chart(countryDistributionCanvas, {
    type: 'bar',
    data: {
        labels: countryDistributionCanvas.data('countries').split(','),
        datasets: [{
            data: countryDistributionCanvas.data('reputations').split(','),
            backgroundColor: getCountryColors(),
        }],
    },
    options: {
        legend: {
            display: false,
        },
    },
});

function getCountryColors() {
    return [
        '#7d3865',
        '#c1a7b0',
        '#f8b703',
        '#c1a7b0',
        '#0fa2a9',
        '#f1d3a1',
        '#e3dbd9',
        '#e6eff6',
        '#89b4c4',
        '#548999',
        '#553973',
        '#aa6288',
        '#d49f99',
        '#968d52',
        '#dd7035',
    ];
}
