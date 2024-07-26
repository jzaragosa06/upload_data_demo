{{-- 
<!DOCTYPE html>
<html>

<head>
    <title>Multivariate Data Processing</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body>
    <div class="container">
        <h1>Multivariate Data Processing</h1>
        <div class="row">
            <div class="col-md-4">
                <h3>Feature Variables</h3>
                @foreach ($headers as $index => $header)
                    @if ($index > 0)
                        <button class="btn btn-info feature-btn"
                            data-index="{{ $index }}">{{ $header }}</button><br>
                    @endif
                @endforeach
            </div>
            <div class="col-md-8">
                <div id="chart-container">
                    <canvas id="chart"></canvas>
                </div>
                <div id="processing-options" style="display: none;">
                    <h3>Data Cleaning and Processing</h3>
                    <form id="processing-form">
                        <label>Fill Missing Value (NaN) with:</label><br>
                        <input type="radio" name="fill-method" value="forward" checked> Forward Fill<br>
                        <input type="radio" name="fill-method" value="backward"> Backward Fill<br>
                        <input type="radio" name="fill-method" value="average"> Average of the series<br>
                        <input type="radio" name="fill-method" value="zero"> Fill with zeros<br>
                        <input type="radio" name="fill-method" value="none"> Do Not Fill<br>
                    </form>
                </div>
            </div>
        </div>
        <button id="next-button" class="btn btn-primary" style="display: none;">Next</button>
    </div>

    <script>
        let chartInstance;
        const data = @json($data);
        const headers = @json($headers);
        let tempData = [...data];
        let currentMethod = 'forward'; // Default fill method

        document.querySelectorAll('.feature-btn').forEach(button => {
            button.addEventListener('click', () => {
                const index = button.getAttribute('data-index');
                const label = headers[index];
                const values = tempData.map(row => parseFloat(row[index]) || null);
                const dates = tempData.map(row => row[0]);

                fillMissingValues(currentMethod, index);
                showChart(label, dates, values);
                document.getElementById('processing-options').style.display = 'block';
                document.getElementById('next-button').style.display = 'block';
            });
        });

        function showChart(label, dates, values) {
            if (chartInstance) {
                chartInstance.destroy();
            }
            const ctx = document.getElementById('chart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: label,
                        data: values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            }
                        }
                    }
                }
            });
        }

        function fillMissingValues(method, index) {
            currentMethod = method; // Save the selected method
            switch (method) {
                case 'forward':
                    for (let i = 1; i < tempData.length; i++) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = tempData[i - 1][index];
                        }
                    }
                    break;
                case 'backward':
                    for (let i = tempData.length - 2; i >= 0; i--) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = tempData[i + 1][index];
                        }
                    }
                    break;
                case 'average':
                    let sum = 0;
                    let count = 0;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][index] !== null && tempData[i][index] !== '') {
                            sum += parseFloat(tempData[i][index]);
                            count++;
                        }
                    }
                    const average = sum / count;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = average;
                        }
                    }
                    break;
                case 'zero':
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = 0;
                        }
                    }
                    break;
                case 'none':
                    // Do nothing
                    break;
            }
        }

        document.querySelectorAll('input[name="fill-method"]').forEach(input => {
            input.addEventListener('change', () => {
                const method = document.querySelector('input[name="fill-method"]:checked').value;
                const activeIndex = document.querySelector('.feature-btn.active').getAttribute(
                'data-index');
                fillMissingValues(method, activeIndex);
                // Refresh the chart with the new filled values
                const label = headers[activeIndex];
                const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                const dates = tempData.map(row => row[0]);
                showChart(label, dates, values);
            });
        });
    </script>
</body>

</html> --}}


<!DOCTYPE html>
<html>

<head>
    <title>Multivariate Data Processing</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body>
    <div class="container">
        <h1>Multivariate Data Processing</h1>
        <div class="row">
            <div class="col-md-4">
                <h3>Feature Variables</h3>
                @foreach ($headers as $index => $header)
                    @if ($index > 0)
                        <button class="btn btn-info feature-btn"
                            data-index="{{ $index }}">{{ $header }}</button><br>
                    @endif
                @endforeach
            </div>
            <div class="col-md-8">
                <div id="chart-container">
                    <canvas id="chart"></canvas>
                </div>
                <div id="processing-options" style="display: none;">
                    <h3>Data Cleaning and Processing</h3>
                    <form id="processing-form">
                        <label>Fill Missing Value (NaN) with:</label><br>
                        <input type="radio" name="fill-method" value="forward"> Forward Fill<br>
                        <input type="radio" name="fill-method" value="backward"> Backward Fill<br>
                        <input type="radio" name="fill-method" value="average"> Average of the series<br>
                        <input type="radio" name="fill-method" value="zero"> Fill with zeros<br>
                        <input type="radio" name="fill-method" value="none"> Do Not Fill<br>
                    </form>
                </div>
            </div>
        </div>
        <button id="next-button" class="btn btn-primary" style="display: none;">Next</button>
    </div>

    <script>
        let chartInstance;
        const data = @json($data);
        const headers = @json($headers);
        let tempData = JSON.parse(JSON.stringify(data)); // Deep copy of data to modify
        let fillMethods = {}; // Store selected fill method for each variable
        let activeIndex = null; // Track the active variable index
        let currentMethod = 'forward'; // Default fill method

        document.querySelectorAll('.feature-btn').forEach(button => {
            button.addEventListener('click', () => {
                activeIndex = button.getAttribute('data-index');
                const label = headers[activeIndex];
                const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                const dates = tempData.map(row => row[0]);

                if (fillMethods[activeIndex]) {
                    currentMethod = fillMethods[activeIndex];
                } else {
                    currentMethod = 'forward'; // Default fill method if not selected before
                }

                fillMissingValues(currentMethod, activeIndex);
                showChart(label, dates, values);
                document.getElementById('processing-options').style.display = 'block';
                document.getElementById('next-button').style.display = 'block';

                // Update the fill method selection
                document.querySelector(`input[name="fill-method"][value="${currentMethod}"]`).checked =
                true;

                // Log data for inspection
                console.log(`Current Data for ${label}:`, tempData.map(row => row[activeIndex]));
            });
        });

        function showChart(label, dates, values) {
            if (chartInstance) {
                chartInstance.destroy();
            }
            const ctx = document.getElementById('chart').getContext('2d');
            chartInstance = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: dates,
                    datasets: [{
                        label: label,
                        data: values,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        x: {
                            type: 'time',
                            time: {
                                unit: 'day'
                            }
                        }
                    }
                }
            });
        }

        function fillMissingValues(method, index) {
            fillMethods[index] = method; // Save the selected method for the current variable
            tempData = JSON.parse(JSON.stringify(data)); // Reset tempData to original data

            switch (method) {
                case 'forward':
                    for (let i = 1; i < tempData.length; i++) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = tempData[i - 1][index];
                        }
                    }
                    break;
                case 'backward':
                    for (let i = tempData.length - 2; i >= 0; i--) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = tempData[i + 1][index];
                        }
                    }
                    break;
                case 'average':
                    let sum = 0;
                    let count = 0;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][index] !== null && tempData[i][index] !== '') {
                            sum += parseFloat(tempData[i][index]);
                            count++;
                        }
                    }
                    const average = sum / count;
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = average;
                        }
                    }
                    break;
                case 'zero':
                    for (let i = 0; i < tempData.length; i++) {
                        if (tempData[i][index] === null || tempData[i][index] === '') {
                            tempData[i][index] = 0;
                        }
                    }
                    break;
                case 'none':
                    // Do nothing
                    break;
            }
        }

        document.querySelectorAll('input[name="fill-method"]').forEach(input => {
            input.addEventListener('change', () => {
                if (activeIndex !== null) {
                    const method = document.querySelector('input[name="fill-method"]:checked').value;
                    fillMissingValues(method, activeIndex);
                    // Refresh the chart with the new filled values
                    const label = headers[activeIndex];
                    const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                    const dates = tempData.map(row => row[0]);
                    showChart(label, dates, values);

                    // Log data for inspection
                    console.log(`Filled Data for ${label}:`, tempData.map(row => row[activeIndex]));
                }
            });
        });
    </script>
</body>

</html>
