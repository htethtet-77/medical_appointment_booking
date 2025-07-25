 <?php 
$user = $_SESSION['current_user'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Appointments</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f8f9fa;
            color: #333;
            line-height: 1.5;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }

        .header p {
            color: #666;
            font-size: 16px;
        }

        .appointments {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .appointment {
            background: white;
            border-radius: 8px;
            padding: 25px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            border-left: 4px solid #e74c3c;
        }

        .appointment.upcoming {
            border-left-color: #3498db;
        }

        .appointment.completed {
            border-left-color: #27ae60;
        }

        .appointment.cancelled {
            border-left-color: #95a5a6;
            opacity: 0.7;
        }

        .appointment-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .date-time {
            font-size: 20px;
            font-weight: 600;
            color: #2c3e50;
        }

        .status {
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 600;
            text-transform: uppercase;
        }

        .status.upcoming {
            background: #e3f2fd;
            color: #1976d2;
        }

        .status.completed {
            background: #e8f5e8;
            color: #2e7d32;
        }

        .status.cancelled {
            background: #f5f5f5;
            color: #757575;
        }

        .appointment-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }

        .detail {
            display: flex;
            flex-direction: column;
        }

        .detail-label {
            font-size: 12px;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 6px;
        }

        .detail-value {
            font-size: 16px;
            color: #333;
            font-weight: 500;
        }

        /* Mobile styles */
        @media (max-width: 600px) {
            .container {
                padding: 15px;
            }

            .header {
                padding: 20px;
                margin-bottom: 20px;
            }

            .header h1 {
                font-size: 24px;
            }

            .appointment {
                padding: 20px;
            }

            .appointment-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .date-time {
                font-size: 18px;
            }

            .appointment-details {
                grid-template-columns: 1fr;
                gap: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>My Appointments</h1>
            <p><?php echo htmlspecialchars($user['name']); ?>- Your appointment history and status</p>
        </div>

        <div class="appointments">
            <!-- Today's Appointment -->
            <div class="appointment upcoming">
                <div class="appointment-header">
                    <div class="date-time">Today, July 25 at 2:30 PM</div>
                    <div class="status upcoming">Upcoming</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. Michael Chen</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">Dermatology</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">Building B, Room 102</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Fee</div>
                        <div class="detail-value">$120</div>
                    </div>
                </div>
            </div>

            <!-- Future Appointment -->
            <div class="appointment upcoming">
                <div class="appointment-header">
                    <div class="date-time">August 2, 2025 at 9:00 AM</div>
                    <div class="status upcoming">Upcoming</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. Sarah Wilson</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">Cardiology</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Location</div>
                        <div class="detail-value">Building A, Room 205</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Fee</div>
                        <div class="detail-value">$150</div>
                    </div>
                </div>
            </div>

            <!-- Completed Appointment -->
            <div class="appointment completed">
                <div class="appointment-header">
                    <div class="date-time">July 20, 2025 at 11:00 AM</div>
                    <div class="status completed">Completed</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. Emily Rodriguez</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">General Medicine</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Fee</div>
                        <div class="detail-value">$100 (Paid)</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Notes</div>
                        <div class="detail-value">Regular checkup completed</div>
                    </div>
                </div>
            </div>

            <!-- Cancelled Appointment -->
            <div class="appointment cancelled">
                <div class="appointment-header">
                    <div class="date-time">July 18, 2025 at 3:00 PM</div>
                    <div class="status cancelled">Cancelled</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. David Thompson</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">Orthopedics</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Reason</div>
                        <div class="detail-value">Cancelled by patient</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Cancellation Date</div>
                        <div class="detail-value">July 17, 2025</div>
                    </div>
                </div>
            </div>

            <!-- Completed Appointment -->
            <div class="appointment completed">
                <div class="appointment-header">
                    <div class="date-time">July 15, 2025 at 10:30 AM</div>
                    <div class="status completed">Completed</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. Lisa Park</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">Ophthalmology</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Fee</div>
                        <div class="detail-value">$140 (Paid)</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Notes</div>
                        <div class="detail-value">Eye examination completed</div>
                    </div>
                </div>
            </div>

            <!-- Older Completed Appointment -->
            <div class="appointment completed">
                <div class="appointment-header">
                    <div class="date-time">May 17, 2025 at 10:00 AM</div>
                    <div class="status completed">Completed</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. Daniel Kim</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">General Physician</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Fee</div>
                        <div class="detail-value">$120 (Paid)</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Notes</div>
                        <div class="detail-value">Annual physical examination</div>
                    </div>
                </div>
            </div>

            <!-- Old Cancelled Appointment -->
            <div class="appointment cancelled">
                <div class="appointment-header">
                    <div class="date-time">May 15, 2025 at 10:00 AM</div>
                    <div class="status cancelled">Cancelled</div>
                </div>
                <div class="appointment-details">
                    <div class="detail">
                        <div class="detail-label">Doctor</div>
                        <div class="detail-value">Dr. Daniel Kim</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Department</div>
                        <div class="detail-value">General Physician</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Reason</div>
                        <div class="detail-value">Doctor unavailable</div>
                    </div>
                    <div class="detail">
                        <div class="detail-label">Cancellation Date</div>
                        <div class="detail-value">May 14, 2025</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>