@extends('home')

@section('content')

    <!-- FullCalendar CSS -->
    <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.css" rel="stylesheet">
    <!-- FullCalendar JS -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.0/main.min.js"></script>
    <!-- Custom CSS -->
     
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background: #f8f9fa;
        }

        #calendar {
            max-width: 900px;
            margin: 50px auto;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #fff;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
        }

        .fc-header-toolbar {
            background: #f1f1f1;
            padding: 10px;
            border-bottom: 1px solid #e0e0e0;
            border-radius: 10px 10px 0 0;
        }

        .fc-toolbar-title {
            font-size: 20px;
            color: #333;
            font-weight: bold;
        }

        .fc-button {
            background: #d6c5ff;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 5px 12px;
            font-size: 14px;
            transition: 0.3s;
        }

        .fc-button:hover {
            background: #a47cff;
            color: #fff;
        }

        .fc-daygrid-event {
            background: #164da0;
            color: #ffffff !important;
            border: none;
            border-radius: 5px;
            padding: 2px 6px;
            font-size: 12px;
            margin: 2px;
        }

        .fc-daygrid-day {
            border: 1px solid #eaeaea;
        }

        .fc-day-today {
            background: #e8f5ff;
            border: 2px solid #2196f3;
        }

        .fc-button-group > .fc-button {
            margin-right: 5px;
        }
    </style>

    
    <div id="calendar"></div>

  <script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');

        // Events data passed from the controller
        var events = @json($events);

        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            events: events, // Assign dynamic events to FullCalendar
            themeSystem: 'standard',
            dateClick: function(info) {
                alert('You clicked on: ' + info.dateStr);
            },
            eventClick: function(info) {
                alert('Event: ' + info.event.title + '\nFollow-Up Date: ' + info.event.start.toISOString().slice(0, 10));
            }
        });

        calendar.render();
    });
</script>




@endsection
