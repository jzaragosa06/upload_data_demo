<!DOCTYPE html>
<html>

<head>
    <title>Time Series Graph</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body>
    <h1>Time Series Graph</h1>
    <canvas id="timeSeriesChart"></canvas>
    <script>
        // Sample data from server
        const data = @json($data);


        // Ensure data is an array
        if (Array.isArray(data)) {
            // Convert dates to ISO format for Chart.js
            const labels = data.map(entry => new Date(entry.date)).reverse();
            const values = data.map(entry => parseFloat(entry.meantemp)).reverse();

            const ctx = document.getElementById('timeSeriesChart').getContext('2d');
            const chart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Mean Temperature',
                        data: values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 2,
                        fill: false
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `Date: ${context.label}, Temp: ${context.raw}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day',
                                tooltipFormat: 'MMM D, YYYY'
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Mean Temperature'
                            }
                        }
                    }
                }
            });
        } else {
            console.error('Data is not an array:', data);
        }
    </script>
</body>

</html>
