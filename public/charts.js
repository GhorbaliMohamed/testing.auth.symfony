document.addEventListener('DOMContentLoaded', function() {
    fetchChartData(
        '/mainadmin/dashboard', // URL path for fetching dashboard data
        '/mainadmin/dashboard' // URL path for fetching bar chart data
    );
});

function fetchChartData(pieChartDataUrl, barChartDataUrl) {
    Promise.all([
        fetch(pieChartDataUrl).then(response => response.json()),
        fetch(barChartDataUrl).then(response => response.json())
    ])
    .then(([pieChartData, barChartData]) => {
        populatePieChart(pieChartData);
        populateBarChart(barChartData);
    });
}
function populatePieChart(data) {
    const pieChartElement = document.querySelector('.pie-chart');
    pieChartElement.innerHTML = '';

    data.forEach(item => {
        const slice = document.createElement('div');
        slice.classList.add('pie-chart-slice');
        slice.style.backgroundColor = getRandomColor();
        slice.style.width = `${item.value}%`;
        pieChartElement.appendChild(slice);
    });
}

function populateBarChart(data) {
    const barChartElement = document.querySelector('.bar-chart');
    barChartElement.innerHTML = '';

    data.forEach(item => {
        const bar = document.createElement('div');
        bar.classList.add('bar-chart-item');
        bar.style.height = `${item.value}%`;
        bar.style.backgroundColor = getRandomColor();
        barChartElement.appendChild(bar);
    });
}

function getRandomColor() {
    const colors = ['#007bff', '#6c757d', '#28a745', '#dc3545', '#ffc107', '#17a2b8'];
    return colors[Math.floor(Math.random() * colors.length)];
}