<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/dashboard.css">
</head>
<body>
         <?php require APPROOT . '/views/inc/sidebar.php'; ?>
        <!-- Main Content Area -->
        <main class="container mx-auto p-4 md:p-8">
            <!-- Summary Cards Section -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <!-- Total Doctor Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105 duration-300">
                    <i class="fas fa-user-md text-blue-600 text-4xl mb-3"></i>
                    <p class="text-gray-700 text-xl font-semibold mb-1">Total Doctor</p>
                    <p class="text-gray-900 text-3xl font-bold">8</p>
                </div>

                <!-- Total Patient Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105 duration-300">
                    <i class="fas fa-hospital-user text-green-600 text-4xl mb-3"></i>
                    <p class="text-gray-700 text-xl font-semibold mb-1">Total Patient</p>
                    <p class="text-gray-900 text-3xl font-bold">6</p>
                </div>

                <!-- History Card -->
                <div class="bg-white rounded-xl shadow-lg p-6 flex flex-col items-center justify-center text-center transition-transform transform hover:scale-105 duration-300">
                    <i class="fas fa-history text-purple-600 text-4xl mb-3"></i>
                    <p class="text-gray-700 text-xl font-semibold mb-1">History</p>
                    <p class="text-gray-900 text-3xl font-bold">6</p>
                </div>
            </div>

            <!-- Appointment Request Section -->
            <div class="bg-white rounded-xl shadow-lg p-6">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 border-b pb-4">Appointment Request</h2>

                <!-- Responsive Table Container -->
                <div class="overflow-x-auto table-container rounded-lg border border-gray-200">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tl-lg">Id</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider rounded-tr-lg">Action</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <!-- Table Row 1 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">1</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Patient Name</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dr.Daniel</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">120$</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 mr-2">Approve</button>
                                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">Cancel</button>
                                </td>
                            </tr>
                            <!-- Table Row 2 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">2</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Patient Name</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dr.Daniel</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">120$</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 mr-2">Approve</button>
                                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">Cancel</button>
                                </td>
                            </tr>
                            <!-- Table Row 3 -->
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">3</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Patient Name</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">Dr.Daniel</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">120$</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200 mr-2">Approve</button>
                                    <button class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md transition-colors duration-200">Cancel</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
