<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Delivery Calendar</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.css' rel='stylesheet' />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #calendar { max-width: 900px; margin: 50px auto; }
    </style>
</head>
<body>
<div class="container">
    <h3 class="mt-4 mb-4 text-center">My Delivery Calendar</h3>
    <div id="calendar"></div>
</div>

<script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events),
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek'
            }
        });
        calendar.render();
    });
</script>
</body>
</html>
