{{-- <!DOCTYPE html>
<html>

<head>
    <title>Multivariate Data Processing</title>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
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
                    <div id="chart"></div>
                </div>
                <div id="processing-options" style="display: none;">
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
        <button id="next-button" class="btn btn-primary" style="display: none;">Next</button>
        <a id="download-link" style="display: none;">Download Processed Data</a>
    </div>

    <script>
        const data = @json($data);
        const headers = @json($headers);
        let tempData = JSON.parse(JSON.stringify(data));
        let fillMethods = {};
        let activeIndex = null;

        document.querySelectorAll('.feature-btn').forEach(button => {
            button.addEventListener('click', () => {
                activeIndex = button.getAttribute('data-index');
                const label = headers[activeIndex];
                let method = 'forward';

                // Use the previously selected fill method if available
                if (fillMethods[activeIndex]) {
                    method = fillMethods[activeIndex];
                }

                // Fill missing values using the selected or default method
                fillMissingValues(method, activeIndex);

                const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                const dates = tempData.map(row => formatDate(row[0]));

                showChart(label, dates, values);
                document.getElementById('processing-options').style.display = 'block';
                document.getElementById('next-button').style.display = 'block';

                document.querySelector(`input[name="fill-method"][value="${method}"]`).checked = true;
            });
        });

        function showChart(label, dates, values) {
            const trace = {
                x: dates,
                y: values,
                type: 'scatter',
                mode: 'lines+markers',
                name: label,
                line: {
                    shape: 'linear'
                }
            };

            const layout = {
                title: label,
                xaxis: {
                    title: 'Date',
                    type: 'date'
                },
                yaxis: {
                    title: 'Value'
                }
            };

            Plotly.newPlot('chart', [trace], layout);
        }

        function fillMissingValues(method, index) {
            fillMethods[index] = method;
            tempData = JSON.parse(JSON.stringify(data)); // Reset tempData

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
            }
        }

        document.querySelectorAll('input[name="fill-method"]').forEach(input => {
            input.addEventListener('change', () => {
                if (activeIndex !== null) {
                    const method = document.querySelector('input[name="fill-method"]:checked').value;
                    fillMissingValues(method, activeIndex);
                    const label = headers[activeIndex];
                    const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                    const dates = tempData.map(row => formatDate(row[0]));
                    showChart(label, dates, values);
                }
            });
        });

        document.getElementById('next-button').addEventListener('click', () => {
            const csvData = convertToCSV(headers, tempData);
            createDownloadLink(csvData);
        });

        function convertToCSV(headers, data) {
            const csvRows = [];
            csvRows.push(headers.join(','));
            for (const row of data) {
                csvRows.push(row.join(','));
            }
            return csvRows.join('\n');
        }

        function createDownloadLink(csvData) {
            const blob = new Blob([csvData], {
                type: 'text/csv'
            });
            const url = URL.createObjectURL(blob);
            const downloadLink = document.getElementById('download-link');
            downloadLink.href = url;
            downloadLink.download = 'processed_data.csv';
            downloadLink.textContent = 'Download Processed Data';
            downloadLink.style.display = 'block';
        }

        function formatDate(dateStr) {
            const dateParts = dateStr.split('/');
            if (dateParts.length === 3) {
                // Assuming the format is M/D/YYYY
                const [month, day, year] = dateParts;
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            }
            return dateStr; // If it's already in the correct format
        }
    </script>
</body>

</html> --}}

<!DOCTYPE html>
<html>

<head>
    <title>Multivariate Data Processing</title>
    <script src="https://cdn.plot.ly/plotly-latest.min.js"></script>
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
                    <div id="chart"></div>
                </div>
                <div id="processing-options" style="display: none;">
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
        <button id="next-button" class="btn btn-primary" style="display: none;">Next</button>
        <a id="download-link" style="display: none;">Download Processed Data</a>
    </div>

    <script>
        const data = @json($data);
        const headers = @json($headers);
        let tempData = JSON.parse(JSON.stringify(data));
        let fillMethods = {};
        let activeIndex = null;

        document.querySelectorAll('.feature-btn').forEach(button => {
            button.addEventListener('click', () => {
                activeIndex = button.getAttribute('data-index');
                const label = headers[activeIndex];
                let method = 'forward';

                // Use the previously selected fill method if available
                if (fillMethods[activeIndex]) {
                    method = fillMethods[activeIndex];
                }

                // Fill missing values using the selected or default method
                fillMissingValues(method, activeIndex);

                const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                const dates = tempData.map(row => formatDate(row[0]));

                showChart(label, dates, values);
                document.getElementById('processing-options').style.display = 'block';
                document.getElementById('next-button').style.display = 'block';

                document.querySelector(`input[name="fill-method"][value="${method}"]`).checked = true;
            });
        });

        function showChart(label, dates, values) {
            const trace = {
                x: dates,
                y: values,
                type: 'scatter',
                mode: 'lines+markers',
                name: label,
                line: {
                    shape: 'linear'
                }
            };

            const layout = {
                title: label,
                xaxis: {
                    title: 'Date',
                    type: 'date'
                },
                yaxis: {
                    title: 'Value'
                }
            };

            Plotly.newPlot('chart', [trace], layout);
        }

        // function fillMissingValues(method, index) {
        //     fillMethods[index] = method;
        //     tempData = JSON.parse(JSON.stringify(data)); // Reset tempData

        //     switch (method) {
        //         case 'forward':
        //             for (let i = 1; i < tempData.length; i++) {
        //                 if (tempData[i][index] === null || tempData[i][index] === '') {
        //                     tempData[i][index] = tempData[i - 1][index];
        //                 }
        //             }
        //             break;
        //         case 'backward':
        //             for (let i = tempData.length - 2; i >= 0; i--) {
        //                 if (tempData[i][index] === null || tempData[i][index] === '') {
        //                     tempData[i][index] = tempData[i + 1][index];
        //                 }
        //             }
        //             break;
        //         case 'average':
        //             let sum = 0;
        //             let count = 0;
        //             for (let i = 0; i < tempData.length; i++) {
        //                 if (tempData[i][index] !== null && tempData[i][index] !== '') {
        //                     sum += parseFloat(tempData[i][index]);
        //                     count++;
        //                 }
        //             }
        //             const average = sum / count;
        //             for (let i = 0; i < tempData.length; i++) {
        //                 if (tempData[i][index] === null || tempData[i][index] === '') {
        //                     tempData[i][index] = average;
        //                 }
        //             }
        //             break;
        //         case 'zero':
        //             for (let i = 0; i < tempData.length; i++) {
        //                 if (tempData[i][index] === null || tempData[i][index] === '') {
        //                     tempData[i][index] = 0;
        //                 }
        //             }
        //             break;
        //     }
        // }

        function fillMissingValues(method, index) {
            fillMethods[index] = method;
            const dataCopy = JSON.parse(JSON.stringify(data)); // Make a copy of the original data

            for (const [i, row] of dataCopy.entries()) {
                const currentMethod = fillMethods[i] || 'forward';
                switch (currentMethod) {
                    case 'forward':
                        for (let j = 1; j < dataCopy.length; j++) {
                            if (dataCopy[j][i] === null || dataCopy[j][i] === '') {
                                dataCopy[j][i] = dataCopy[j - 1][i];
                            }
                        }
                        break;
                    case 'backward':
                        for (let j = dataCopy.length - 2; j >= 0; j--) {
                            if (dataCopy[j][i] === null || dataCopy[j][i] === '') {
                                dataCopy[j][i] = dataCopy[j + 1][i];
                            }
                        }
                        break;
                    case 'average':
                        let sum = 0;
                        let count = 0;
                        for (let j = 0; j < dataCopy.length; j++) {
                            if (dataCopy[j][i] !== null && dataCopy[j][i] !== '') {
                                sum += parseFloat(dataCopy[j][i]);
                                count++;
                            }
                        }
                        const average = sum / count;
                        for (let j = 0; j < dataCopy.length; j++) {
                            if (dataCopy[j][i] === null || dataCopy[j][i] === '') {
                                dataCopy[j][i] = average;
                            }
                        }
                        break;
                    case 'zero':
                        for (let j = 0; j < dataCopy.length; j++) {
                            if (dataCopy[j][i] === null || dataCopy[j][i] === '') {
                                dataCopy[j][i] = 0;
                            }
                        }
                        break;
                }
            }

            tempData = dataCopy;
        }

        document.getElementById('next-button').addEventListener('click', () => {
            // Fill missing values for all variables before converting to CSV
            headers.slice(1).forEach((_, index) => {
                const method = fillMethods[index] || 'forward';
                fillMissingValues(method, index);
            });

            const csvData = convertToCSV(headers, tempData);
            createDownloadLink(csvData);
        });




        document.querySelectorAll('input[name="fill-method"]').forEach(input => {
            input.addEventListener('change', () => {
                if (activeIndex !== null) {
                    const method = document.querySelector('input[name="fill-method"]:checked').value;
                    fillMissingValues(method, activeIndex);
                    const label = headers[activeIndex];
                    const values = tempData.map(row => parseFloat(row[activeIndex]) || null);
                    const dates = tempData.map(row => formatDate(row[0]));
                    showChart(label, dates, values);
                }
            });
        });

        document.getElementById('next-button').addEventListener('click', () => {
            let finalData = JSON.parse(JSON.stringify(data));

            headers.forEach((header, index) => {
                if (index > 0) { // Skip the date column
                    let method = fillMethods[index] || 'forward';
                    fillMissingValues(method, index);
                }
            });

            const csvData = convertToCSV(headers, tempData);
            createDownloadLink(csvData);
        });

        function convertToCSV(headers, data) {
            const csvRows = [];
            csvRows.push(headers.join(','));
            for (const row of data) {
                csvRows.push(row.join(','));
            }
            return csvRows.join('\n');
        }

        function createDownloadLink(csvData) {
            const blob = new Blob([csvData], {
                type: 'text/csv'
            });
            const url = URL.createObjectURL(blob);
            const downloadLink = document.getElementById('download-link');
            downloadLink.href = url;
            downloadLink.download = 'processed_data.csv';
            downloadLink.textContent = 'Download Processed Data';
            downloadLink.style.display = 'block';
        }

        function formatDate(dateStr) {
            const dateParts = dateStr.split('/');
            if (dateParts.length === 3) {
                // Assuming the format is M/D/YYYY
                const [month, day, year] = dateParts;
                return `${year}-${month.padStart(2, '0')}-${day.padStart(2, '0')}`;
            }
            return dateStr; // If it's already in the correct format
        }
    </script>
</body>

</html>
