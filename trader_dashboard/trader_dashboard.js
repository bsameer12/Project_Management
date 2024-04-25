window.onload = function () {
    var ctx = document.getElementById('orderGraph').getContext('2d');
    var myChart;

    // Function to create and update the chart
    function createOrUpdateChart(labels, data) {
        if (myChart) {
            myChart.destroy(); // Clear previous chart
        }
        myChart = new Chart(ctx, {
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

    // Call updateChartData function with default value 1 to display data for 1 Day
    updateChartData(1);

    // Create a select dropdown for date range selection
    var dateRangeSelect = document.createElement('select');
    dateRangeSelect.innerHTML = `
        <option value="1" selected>1 Day</option>
        <option value="7">7 Days</option>
        <option value="30">30 Days</option>
    `;
    dateRangeSelect.addEventListener('change', function () {
        var selectedValue = parseInt(dateRangeSelect.value);
        updateChartData(selectedValue);
    });

    // Append the select dropdown to the .graph-section element
    var graphSection = document.querySelector('.graph-section');
    graphSection.appendChild(dateRangeSelect);

    // Function to update chart data based on selected date range
    function updateChartData(selectedValue) {
        var endDate = new Date();
        var startDate = new Date();
        var labels = [];
        var data = [];
        if (selectedValue === 1) {
            // For 1 Day
            startDate.setDate(startDate.getDate() - 1);
            labels.push('1 Day');
            for (var i = 0; i < 24; i++) {
                labels.push(i + ':00'); // Push hour in 24-hour format
                // Generate random data for demonstration (replace with actual data)
                data.push(Math.floor(Math.random() * 50));
            }
        } else if (selectedValue === 7) {
            // For 7 Days
            startDate.setDate(startDate.getDate() - 7);
            labels.push('7 Days');
            for (var i = 0; i < 7; i++) {
                labels.push(startDate.toLocaleDateString());
                // Generate random data for demonstration (replace with actual data)
                data.push(Math.floor(Math.random() * 50));
                startDate.setDate(startDate.getDate() + 1); // Move to next day
            }
        } else if (selectedValue === 30) {
            // For 30 Days
            startDate.setDate(startDate.getDate() - 30);
            labels.push('30 Days');
            for (var i = 0; i < 30; i++) {
                labels.push(startDate.toLocaleDateString());
                // Generate random data for demonstration (replace with actual data)
                data.push(Math.floor(Math.random() * 50));
                startDate.setDate(startDate.getDate() + 1); // Move to next day
            }
        }
        createOrUpdateChart(labels, data);
    }
};




