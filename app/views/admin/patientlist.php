 <?php require APPROOT . '/views/inc/header.php'; ?>
<?php require APPROOT . '/views/inc/sidebar.php'; ?>
<body>

    <main class="patient-container">
        <h1 class="page-title">Patient List</h1>

        <div class="search-bar">
            <i class="fas fa-search"></i>
            <input type="text" placeholder="Patient">
        </div>

        <div class="patient-table-container">
            <table class="patient-table">
                <thead>
                    <tr>
                        <th>Patient id</th>
                        <th>Patient Name</th>
                        <th>Gender</th>
                        <th>Ph No</th>
                        <th>History</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td data-label="Patient id">1</td>
                        <td data-label="Patient Name">John</td>
                        <td data-label="Gender">Male</td>
                        <td data-label="Ph No">09</td>
                        <td data-label="History"><a href="#" class="view-button">View</a></td>
                    </tr>
                    <tr>
                        <td data-label="Patient id">2</td>
                        <td data-label="Patient Name">Smith</td>
                        <td data-label="Gender">Male</td>
                        <td data-label="Ph No">09</td>
                        <td data-label="History"><a href="#" class="view-button">View</a></td>
                    </tr>
                    <tr>
                        <td data-label="Patient id">3</td>
                        <td data-label="Patient Name">John</td>
                        <td data-label="Gender">Male</td>
                        <td data-label="Ph No">09</td>
                        <td data-label="History"><a href="#" class="view-button">View</a></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>

