<?php
function getAvailableSlots($startTime, $endTime, $intervalMinutes = 20) {
    $slots = [];
    $start = strtotime($startTime);
    $end = strtotime($endTime) - ($intervalMinutes * 60);
    while ($start <= $end) {
        $slots[] = date('H:i:s', $start);
        $start += $intervalMinutes * 60;
    }
    return $slots;
}

function getBookedTimes($appointments, $selectedDate) {
    return array_column(array_filter($appointments, function ($a) use ($selectedDate) {
        return date('Y-m-d', strtotime($a['appointment_date'])) === $selectedDate;
    }), 'appointment_time');
}

function filterFutureAvailableSlots($slots, $bookedTimes, $selectedDate) {
    $now = new DateTime('now', new DateTimeZone('Asia/Yangon'));
    $selected = new DateTime($selectedDate, new DateTimeZone('Asia/Yangon'));
    
    return array_values(array_filter($slots, function ($slot) use ($selectedDate, $selected, $now, $bookedTimes) {
        $slotTime = new DateTime("$selectedDate $slot", new DateTimeZone('Asia/Yangon'));
        return !in_array($slot, $bookedTimes) && ($selected > $now || $slotTime > $now);
    }));
}
