{{-- <!DOCTYPE html>
<html>
<head>
    <title>Multivariate Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>

</head>
<body>
    <h1>Multivariate Data</h1>
    <div>
        @foreach ($headers as $header)
            @if ($loop->last) 
                @continue 
            @endif
            <button onclick="showColumnData('{{ $header }}')">{{ $header }}</button>
        @endforeach
    </div>

    <div id="data-display">
        <!-- JavaScript will dynamically populate this section with the selected column's data -->
        <canvas id="multivariateChart"></canvas>
    </div>

    <script>
        const data = @json($data);

        function showColumnData(column) {
            const labels = data.map(row => row['Date']);
            const columnData = data.map(row => row[column] || null); // Use null for missing values

            const ctx = document.getElementById('multivariateChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: column,
                        data: columnData,
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
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            let html = `
                <form action="{{ route('process_multivariate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="column" value="${column}">
                    <label for="fill_method">Fill Missing Values with:</label>
                    <select name="fill_method" required>
                        <option value="forward_fill">Forward Fill</option>
                        <option value="backward_fill">Backward Fill</option>
                        <option value="average">Average of the Series</option>
                        <option value="fill_zero">Fill with Zeros</option>
                    </select>
                    <button type="submit">Process</button>
                </form>
            `;

            document.getElementById('data-display').innerHTML = html;
        }
    </script>
</body>
</html> --}}

<!DOCTYPE html>
<html>

<head>
    <title>Multivariate Data</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns"></script>
</head>

<body>
    <h1>Multivariate Data</h1>
    <div>
        @foreach ($headers as $header)
            @if ($loop->first || $loop->last)
                @continue
            @endif
            {{-- <button onclick="showColumnData('{{ $header }}')">{{ $header }}</button> --}}
            <button onclick="showColumnData('{{ $header }}')">{{ $header }}</button>
        @endforeach
    </div>

    <div id="data-display">
        <!-- JavaScript will dynamically populate this section with the selected column's data -->
        <canvas id="multivariateChart"></canvas>
    </div>

    {{-- <script>
        // Debugging output
        console.log('Data:', @json($data));

        const data = @json($data);

        function showColumnData(column) {
            console.log('Selected Column:', column);

            // Check the format of the data
            if (!Array.isArray(data)) {
                console.error('Data is not an array:', data);
                return;
            }

            const labels = data.map(row => row['Date']);
            const columnData = data.map(row => row[column] || null); // Use null for missing values

            const ctx = document.getElementById('multivariateChart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: column,
                        data: columnData,
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
                            }
                        },
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });

            let html = `
                <form action="{{ route('process_multivariate') }}" method="POST">
                    @csrf
                    <input type="hidden" name="column" value="${column}">
                    <label for="fill_method">Fill Missing Values with:</label>
                    <select name="fill_method" required>
                        <option value="forward_fill">Forward Fill</option>
                        <option value="backward_fill">Backward Fill</option>
                        <option value="average">Average of the Series</option>
                        <option value="fill_zero">Fill with Zeros</option>
                    </select>
                    <button type="submit">Process</button>
                </form>
            `;

            document.getElementById('data-display').innerHTML = html;
        }
    </script> --}}

    <script>
        // Convert JSON data to an array if it is not already
        let data = @json($data);

        // Check if data is an object and convert to an array
        if (typeof data === 'object' && !Array.isArray(data)) {
            data = Object.values(data);
        }

        // Debugging output
        console.log('Converted Data:', data);

        // Ensure the script runs after the DOM is fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            function showColumnData(column) {
                console.log('Selected Column:', column);

                // Ensure data is correctly formatted
                if (!Array.isArray(data)) {
                    console.error('Data is not an array:', data);
                    return;
                }

                // Extract labels and column data
                const labels = data.map(row => row['date']); // Use the correct date column name
                const columnData = data.map(row => row[column] || null); // Use null for missing values

                // Get the canvas element and context
                const canvas = document.getElementById('multivariateChart');
                if (!canvas) {
                    console.error('Canvas element not found.');
                    return;
                }

                const ctx = canvas.getContext('2d');
                if (!ctx) {
                    console.error('Unable to get canvas context.');
                    return;
                }

                // Clear any existing chart before rendering a new one
                if (window.myChart) {
                    window.myChart.destroy();
                }

                // Create a new chart
                window.myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: column,
                            data: columnData,
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
                                }
                            },
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });

                // Create the HTML for the form
                let html = `
                    <form action="{{ route('process_multivariate') }}" method="POST">
                        @csrf
                        <input type="hidden" name="column" value="${column}">
                        <label for="fill_method">Fill Missing Values with:</label>
                        <select name="fill_method" required>
                            <option value="forward_fill">Forward Fill</option>
                            <option value="backward_fill">Backward Fill</option>
                            <option value="average">Average of the Series</option>
                            <option value="fill_zero">Fill with Zeros</option>
                        </select>
                        <button type="submit">Process</button>
                    </form>
                `;

                document.getElementById('data-display').innerHTML = html;
            }

            // Example of calling showColumnData with a column name
            // Uncomment and adjust according to your requirements
            // showColumnData('humidity');
        });
    </script>


</body>

</html>
