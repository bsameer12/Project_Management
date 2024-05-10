window.onload = function () {
    var ctxLine = document.getElementById('orderGraph').getContext('2d');
    var ctxPie = document.getElementById('salesbar').getContext('2d');
    var myLineChart;
    var myPieChart;

    // Function to create and update the line chart
    function createOrUpdateLineChart(labels, data) {
        if (myLineChart) {
            myLineChart.destroy(); // Clear previous chart
        }
        myLineChart = new Chart(ctxLine, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Order Number vs Time',
                    data: data,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true
                        }
                    }]
                },
                plugins: {
                    datalabels: {
                        display: false
                    }
                }
            }
        });
    }

    // Function to create and update the pie chart
    function createOrUpdatePieChart(labels, data) {
        if (myPieChart) {
            myPieChart.destroy(); // Clear previous chart
        }
        myPieChart = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Portions of Products Sold',
                    data: data,
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.5)',
                        'rgba(54, 162, 235, 0.5)',
                        'rgba(255, 206, 86, 0.5)',
                        'rgba(75, 192, 192, 0.5)',
                        'rgba(153, 102, 255, 0.5)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
            }
        });
    }

    // Initial data for demonstration
    var lineLabels = ['1 Day', '2 Day', '3 Day', '4 Day', '5 Day'];
    var lineData = [10, 20, 30, 40, 50];
    createOrUpdateLineChart(lineLabels, lineData);

    var pieLabels = ['Product A', 'Product B', 'Product C', 'Product D', 'Product E'];
    var pieData = [20, 30, 10, 15, 25];
    createOrUpdatePieChart(pieLabels, pieData);
};

function createSalesGraph() {
    // Get canvas element
    var ctx = document.getElementById('salesBarGraph').getContext('2d');

    // Sample data (you would replace this with your actual data)
    var products = ['Product A', 'Product B', 'Product C', 'Product D'];
    var timesSold = [20, 15, 30, 25]; // Number of times each product is sold

    // Create the bar graph
    var salesBarGraph = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: products,
            datasets: [{
                label: 'Number of Times Sold',
                data: timesSold,
                backgroundColor: 'rgba(54, 162, 235, 0.5)', // Blue color with opacity
                borderColor: 'rgba(54, 162, 235, 1)', // Blue color
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

// Call the function to create the sales graph
createSalesGraph();