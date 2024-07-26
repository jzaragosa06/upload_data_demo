<!DOCTYPE html>
<html>

<head>
    <title>Univariate Data Processing</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body>
    <div class="container">
        <h1>Univariate Data Processing</h1>
        <div class="row">
            <div class="col-md-12">
                <div id="chart-container">
                    <canvas id="chart"></canvas>
                </div>
                <div id="processing-options">
                    <h3>Data Cleaning and Processing</h3>
                    <form id="processing-form">
                        <label>Fill Missing Value (NaN) with:</label><br>
                        <input type="radio" name="fill-method" value="forward"> Forward Fill<br>
                        <input type="radio" name="fill-method" value="backward"> Backward Fill<br>
                        <input type="radio" name="fill-method" value="average"> Average of the series<br>
                        <input type="radio" name="fill-method" value="zero"> Fill with zeros<br>
                    </form>
                </div>
            </div>
        </div>
        <button id="next-button" class="btn btn-primary">Next</button>
    </div>

    <script>
        const originalData = @json($data);
        const headers = @json($headers);
        let chartInstance = null; // Variable to store the current chart instance

        const chartCanvas = document.getElementById('chart');

        // Ensure that data is valid
        if (originalData && originalData.length > 0 && headers.length > 1) {
            updateChart(originalData);
        } else {
            console.error('Invalid data or headers');
        }

        function updateChart(data) {
            // Destroy existing chart if it exists
            if (chartInstance) {
                chartInstance.destroy();
            }

            const values = data.map(row => parseFloat(row[1]) || null); // Handle NaN as null
            const dates = data.map(row => new Date(row[0]));
            const label = headers[1];

            chartInstance = new Chart(chartCanvas.getContext('2d'), {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: label,
                        data: values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        fill: false
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            },
                            title: {
                                display: true,
                                text: 'Date'
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: label
                            }
                        }
                    }
                }
            });
        }

        function fillMissingValues(method) {
            // Create a new copy of the original data
            let tempData = JSON.parse(JSON.stringify(originalData));

            switch (method) {
                case 'forward':
                    for (let i = 1; i < tempData.length; i++) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = tempData[i - 1][1];
                        }
                    }
                    break;
                case 'backward':
                    for (let i = tempData.length - 2; i >= 0; i--) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = tempData[i + 1][1];
                        }
                    }
                    break;
                case 'average':
                    let sum = 0;
                    let count = 0;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][1] !== null && tempData[i][1] !== '') {
                            sum += parseFloat(tempData[i][1]);
                            count++;
                        }
                    }
                    const average = sum / count;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = average;
                        }
                    }
                    break;
                case 'zero':
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][1] === null || tempData[i][1] === '') {
                            tempData[i][1] = 0;
                        }
                    }
                    break;
            }

            
            updateChart(tempData);
        }

        document.querySelectorAll('input[name="fill-method"]').forEach(input => {
            input.addEventListener('change', () => {
                const method = document.querySelector('input[name="fill-method"]:checked').value;
                fillMissingValues(method);
            });
        });
    </script>
</body>

</html> 
