<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Students Report Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .chart-container {
            width: 100%;
            max-width: 200px;
            margin: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            z-index: 1;
            background-color: #343a40;
            color: white;
            font-size: 1.2em;
            cursor: pointer;
        }
        tbody td {
            font-size: 20px;
            text-transform: capitalize;
            text-align: center;
            vertical-align: middle;
            font-weight: 500;
        }
        tbody tr {
            background-color: #fff;
        }
        tbody tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-success {
            color: green;
        }
        .text-danger {
            color: red;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container my-5">
        <h1 id="dashboard-title" class="text-center mb-4">Student Report Dashboard</h1>
        <input type="text" id="search-bar" class="form-control mb-4" placeholder="Search by student name or course">
        <div id="pagination-top" class="mb-4 d-flex justify-content-end">
            <ul class="pagination">
                {!! $students->links() !!}
            </ul>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-hover" id="students-table">
                <thead class="table-dark text-center">
                    <tr>
                        <th onclick="sortTable(0)">Student Name</th>
                        <th onclick="sortTable(1)">Course</th>
                        <th onclick="sortTable(2)">Grade</th>
                        <th onclick="sortTable(3)">Attendance</th>
                        <th onclick="sortTable(4)">Progress</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($students as $student)
                        @foreach ($student->courses as $course)
                            <tr>
                                <td>{{ $student->name }}</td>
                                <td>{{ $course->title }}</td>
                                <td>{{ $course->pivot->grade }}%</td>
                                <td class="attendance-cell">{{ $course->pivot->attended ? 'Present' : 'Absent' }}</td>
                                <td>
                                    <div class="chart-container">
                                        <canvas id="progressChart{{ $student->id }}{{ $course->id }}"></canvas>
                                    </div>
                                    <script>
                                        new Chart(document.getElementById('progressChart{{ $student->id }}{{ $course->id }}'), {
                                            type: 'bar',
                                            data: {
                                                labels: ['Grade', 'Attendance'],
                                                datasets: [{
                                                    label: 'Student Progress',
                                                    data: [
                                                        {{ $course->pivot->grade }},
                                                        {{ $course->pivot->attended ? 100 : 0 }}
                                                    ],
                                                    backgroundColor: ['#2222ff', '#4CAF50']
                                                }]
                                            },
                                            options: {
                                                responsive: true,
                                                maintainAspectRatio: false,
                                                plugins: {
                                                    legend: { display: false }
                                                },
                                                scales: {
                                                    y: {
                                                        beginAtZero: true,
                                                        max: 100,
                                                        title: {
                                                            display: true,
                                                            text: 'Percentage (%)',
                                                            color: '#333333'
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    </script>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach
                </tbody>
            </table>
        </div>
        <div id="pagination-bottom" class="d-flex justify-content-end mt-4">
            {!! $students->links() !!}
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Search Bar Functionality
        document.getElementById('search-bar').addEventListener('input', function() {
            var searchTerm = this.value.toLowerCase();
            var rows = document.querySelectorAll('#students-table tbody tr');
            rows.forEach(function(row) {
                var studentName = row.querySelector('td:nth-child(1)').textContent.toLowerCase();
                var courseTitle = row.querySelector('td:nth-child(2)').textContent.toLowerCase();
                if (studentName.includes(searchTerm) || courseTitle.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Set Attendance Cell Colors
        document.querySelectorAll('.attendance-cell').forEach(function(cell) {
            if (cell.textContent.trim() === 'Present') {
                cell.classList.add('text-success');
            } else if (cell.textContent.trim() === 'Absent') {
                cell.classList.add('text-danger');
            }
        });

        // Sort Table Function
        let sortDirection = {};
        function sortTable(columnIndex) {
            const table = document.getElementById("students-table");
            const tbody = table.querySelector("tbody");
            const rows = Array.from(tbody.rows);
            
            if (!sortDirection[columnIndex]) sortDirection[columnIndex] = true;
            sortDirection[columnIndex] = !sortDirection[columnIndex];

            rows.sort((a, b) => {
                const cellA = a.cells[columnIndex].textContent.trim();
                const cellB = b.cells[columnIndex].textContent.trim();
                
                if (!isNaN(parseFloat(cellA)) && !isNaN(parseFloat(cellB))) {
                    return sortDirection[columnIndex] 
                        ? parseFloat(cellA) - parseFloat(cellB) 
                        : parseFloat(cellB) - parseFloat(cellA);
                } else {
                    return sortDirection[columnIndex] 
                        ? cellA.localeCompare(cellB) 
                        : cellB.localeCompare(cellA);
                }
            });

            rows.forEach(row => tbody.appendChild(row));
        }
    </script>
</body>
</html>
