<?php
session_start();
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: admin_login.php");
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Load Tailwind CSS CDN for utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Use Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <!-- LINK TO CUSTOM STYLES -->
    <!-- Note: This path assumes styles.css is in the same directory -->
    <link rel="stylesheet" href="admin.css"> 
</head>
<body class="flex h-screen overflow-hidden">

    <!-- 1. Sidebar -->
    <aside class="sidebar flex flex-col p-6 h-full border-r border-gray-100">
        <div class="flex items-center space-x-2 text-xl font-bold text-gray-800 mb-10">
            <svg class="w-6 h-6 text-blue-500" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h.01a1 1 0 100-2H10zm3 0a1 1 0 000 2h.01a1 1 0 100-2H13z" clip-rule="evenodd"></path>
            </svg>
            <span>CareDoc</span>
        </div>

        <nav class="space-y-2 flex-grow">
            <!-- Appointments (Active by default) -->
            <a href="#" data-view="appointments" class="flex items-center p-3 rounded-xl active-link">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h.01M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2zm3-4h.01M9 17h.01M7 17h.01M13 17h.01M15 17h.01M17 17h.01M13 13h.01M15 13h.01M17 13h.01"></path></svg>
                Appointments
            </a>

            <!-- Patients -->
            <a href="#" data-view="patients" class="flex items-center p-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-blue-500">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M12 20V8m-4 4h8m-4-4h.01"></path></svg>
                Patients
            </a>

            <!-- Messages -->
            <a href="#" data-view="messages" class="flex items-center p-3 rounded-xl text-gray-600 hover:bg-gray-50 hover:text-blue-500">
                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h.01M12 16h.01M9 6h6a2 2 0 012 2v8a2 2 0 01-2 2H9a2 2 0 01-2-2V8a2 2 0 012-2z"></path></svg>
                Messages
            </a>
        </nav>
    </aside>

    <!-- 2. Main Content -->
    <main class="flex-grow flex flex-col p-6 overflow-auto">

        <!-- Header -->
        <header class="flex justify-between items-center pb-6 border-b border-gray-200 sticky top-0 bg-f7f9fb z-10">
            <h1 class="text-3xl font-semibold text-gray-800" id="main-title">Appointments</h1>
            <div class="flex items-center space-x-3">
                <!-- Only Profile Icon remains -->
               
                <img src="https://placehold.co/40x40/3b82f6/ffffff?text=CT" alt="User Avatar" class="w-10 h-10 rounded-full object-cover border-2 border-blue-500">
                <a href="logout.php" class="text-red-500 hover:underline">Logout</a>

            </div>
        
        </header>
        
        <!-- --- APPOINTMENTS VIEW --- -->
        <section id="appointments-view" class="flex flex-col flex-grow">
            <!-- Search and Filter Bar -->
            <div class="flex items-center justify-between mt-6 mb-4 p-4 bg-white rounded-xl shadow-sm border border-gray-100">
                <div class="flex items-center space-x-4">
                    <div class="text-lg font-medium text-gray-700" id="appointment-count">0 All Appointments</div>
                </div>
                <div class="flex items-center space-x-3">
                    <input type="text" placeholder="Search Appointments..." class="p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 transition duration-150">
                    <button class="p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition duration-150">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                    </button>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-x-auto flex-grow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="appointments-table-body">
                        <!-- Table rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- --- PATIENTS VIEW --- -->
        <section id="patients-view" class="hidden flex flex-col flex-grow">
            <h2 class="text-xl font-semibold text-gray-700 mt-6 mb-4">Registered Patients List</h2>
            <div class="bg-white rounded-xl shadow-lg overflow-x-auto flex-grow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient ID</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Appointment</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Appointments</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="patients-table-body">
                        <!-- Table rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </section>

        <!-- --- MESSAGES VIEW --- -->
        <section id="messages-view" class="hidden flex flex-col flex-grow">
            <h2 class="text-xl font-semibold text-gray-700 mt-6 mb-4">Patient Messages</h2>
            <div class="bg-white rounded-xl shadow-lg overflow-x-auto flex-grow">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone Number</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Message</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="messages-table-body">
                        <!-- Table rows will be inserted here by JavaScript -->
                    </tbody>
                </table>
            </div>
        </section>

    </main>

    <script>
        // Initial empty data lists as requested
        let appointments = [];
        let patients = [];
        let messages = [];

        // DOM element references
        const appointmentsTableBody = document.getElementById('appointments-table-body');
        const appointmentCountElement = document.getElementById('appointment-count');
        const patientsTableBody = document.getElementById('patients-table-body');
        const messagesTableBody = document.getElementById('messages-table-body');

        // --- View Management (Simple Client-Side Router) ---
        let currentView = 'appointments';
        const views = {
            'appointments': { element: document.getElementById('appointments-view'), render: renderAppointmentsTable, title: "All Appointments" },
            'patients': { element: document.getElementById('patients-view'), render: renderPatientsTable, title: "Registered Patients" },
            'messages': { element: document.getElementById('messages-view'), render: renderMessagesTable, title: "Patient Messages" }
        };

        function showView(viewName) {
            currentView = viewName;
            document.getElementById('main-title').textContent = views[viewName].title;

            // Hide all views
            Object.keys(views).forEach(key => {
                views[key].element.classList.add('hidden');
            });
            
            // Show the active view and render its data
            views[viewName].element.classList.remove('hidden');
            views[viewName].render();
            
            // Update active link styling
            document.querySelectorAll('nav a').forEach(link => link.classList.remove('active-link'));
            document.querySelector(`nav a[data-view="${viewName}"]`).classList.add('active-link');
        }

        // --- Utility Functions ---

        // Function to mark an appointment as done and remove it from the list
        function markAsDone(patientId) {
            const initialLength = appointments.length;
            
            // Filter out the appointment that matches the patientId
            appointments = appointments.filter(appt => appt.id !== patientId);
            
            if (appointments.length < initialLength) {
                renderAppointmentsTable();
                console.log(`Appointment for Patient ID ${patientId} marked as Done and removed.`);
                showToast(`Appointment #${patientId} completed and removed!`);
            } else {
                console.error(`Could not find appointment with ID ${patientId} to mark as done.`);
            }
        }

        // Simple toast/message box function (no alert)
        function showToast(message) {
            let toast = document.getElementById('toast-message');
            if (!toast) {
                toast = document.createElement('div');
                toast.id = 'toast-message';
                toast.className = 'fixed bottom-5 right-5 p-4 bg-gray-800 text-white rounded-xl shadow-xl transition-opacity duration-300 opacity-0';
                document.body.appendChild(toast);
            }
            toast.textContent = message;
            toast.classList.remove('opacity-0');
            toast.classList.add('opacity-100');
            
            // Hide the toast after 3 seconds
            setTimeout(() => {
                toast.classList.remove('opacity-100');
                toast.classList.add('opacity-0');
            }, 3000);
        }

        // --- Rendering Functions ---

        function renderAppointmentsTable() {
            appointmentsTableBody.innerHTML = '';
            appointmentCountElement.textContent = `${appointments.length} All Appointments`;

            if (appointments.length === 0) {
                appointmentsTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500 text-lg">
                            No appointments scheduled.
                        </td>
                    </tr>
                `;
                return;
            }

            appointments.forEach(appt => {
                const row = appointmentsTableBody.insertRow();
                row.className = 'hover:bg-gray-50 transition duration-150';

                // Data mapping: Patient ID, Name, Date, Time, Type, Status
                const cells = [
                    `#${appt.id}`,
                    `<div class="flex items-center space-x-3">
                        <img src="https://placehold.co/32x32/1d4ed8/ffffff?text=${appt.name.charAt(0)}" onerror="this.src='https://placehold.co/32x32/1d4ed8/ffffff?text=P';" alt="${appt.name}" class="w-8 h-8 rounded-full">
                        <span>${appt.name}</span>
                    </div>`,
                    appt.date,
                    appt.time,
                    appt.type,
                    // Status Toggle Button
                    `<button onclick="markAsDone(${appt.id})" class="done-button inline-flex items-center px-4 py-2 border border-transparent text-sm leading-4 font-medium rounded-full shadow-sm text-white bg-green-500 hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>
                        Done
                    </button>`
                ];

                cells.forEach((content, index) => {
                    const cell = row.insertCell(index);
                    cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
                    cell.innerHTML = content;
                });
            });
        }

        function renderPatientsTable() {
            patientsTableBody.innerHTML = '';

            if (patients.length === 0) {
                patientsTableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-lg">
                            No registered patients yet.
                        </td>
                    </tr>
                `;
                return;
            }

            // Example structure for future patient data (currently empty)
            patients.forEach(patient => {
                const row = patientsTableBody.insertRow();
                row.className = 'hover:bg-gray-50 transition duration-150';

                const cells = [
                    `#${patient.id}`,
                    patient.name,
                    patient.lastApptDate || 'N/A',
                    patient.totalAppts
                ];

                cells.forEach((content, index) => {
                    const cell = row.insertCell(index);
                    cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
                    cell.textContent = content;
                });
            });
        }

        function renderMessagesTable() {
            messagesTableBody.innerHTML = '';

            if (messages.length === 0) {
                messagesTableBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-6 py-8 text-center text-gray-500 text-lg">
                            No new messages.
                        </td>
                    </tr>
                `;
                return;
            }

            // Example structure for future message data (currently empty)
            messages.forEach(message => {
                const row = messagesTableBody.insertRow();
                row.className = 'hover:bg-gray-50 transition duration-150';

                const cells = [
                    message.name,
                    message.email,
                    message.phone,
                    `<div class="truncate max-w-xs">${message.text}</div>` // Truncate long messages
                ];

                cells.forEach((content, index) => {
                    const cell = row.insertCell(index);
                    cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-900';
                    cell.innerHTML = content;
                });
            });
        }

        // --- Initialization ---
        window.onload = function() {
            // Initialize event listeners for sidebar navigation
            document.querySelectorAll('nav a').forEach(link => {
                link.addEventListener('click', (e) => {
                    e.preventDefault();
                    // Get the view name from the data-view attribute
                    showView(e.currentTarget.dataset.view);
                });
            });

            // Start on the appointments view (default)
            showView('appointments'); 
        };

        // Expose functions globally so they can be called from the HTML button's onclick attribute
        window.markAsDone = markAsDone;
        window.showToast = showToast;
    </script>
</body>
</html>