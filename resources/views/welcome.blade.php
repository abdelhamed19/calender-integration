<!DOCTYPE html>
<html>
<head>
    <meta charset='utf-8' />
    <meta content="{{ csrf_token() }}" name="csrf-token" />
    <script src='{{asset('calender/dist/index.global.js')}}'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var calendarEl = document.getElementById('calendar');

            // Retrieve events from localStorage
            var savedEvents = JSON.parse(localStorage.getItem('calendarEvents')) || [];

            var calendar = new FullCalendar.Calendar(calendarEl, {
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridYear,dayGridMonth,timeGridWeek newMeetingButton'
                },
                initialView: 'dayGridYear',
                initialDate: '2023-01-12',
                editable: true,
                selectable: true,
                dayMaxEvents: true,
                customButtons: {
                    newMeetingButton: {
                        text: 'New Meeting',
                        click: function() {
                            var meetingTitle = prompt("Enter meeting title:");
                            var meetingDate = prompt("Enter meeting date (YYYY-MM-DD):");
                            var meetingTime = prompt("Enter meeting time (HH:MM:SS):");

                            if (meetingTitle && meetingDate && meetingTime) {
                                var newEvent = {
                                    title: meetingTitle,
                                    start: meetingDate + 'T' + meetingTime,
                                    allDay: false
                                };

                                // Add event to the calendar
                                calendar.addEvent(newEvent);

                                // Save to localStorage
                                savedEvents.push(newEvent);
                                localStorage.setItem('calendarEvents', JSON.stringify(savedEvents));

                                // Send data to Laravel via AJAX request
                                sendEventsToServer(savedEvents);
                            } else {
                                alert('Please provide valid details!');
                            }
                        }
                    }
                },
                events: savedEvents
            });

            calendar.render();

            // // Function to send events to Laravel backend
            // function sendEventsToServer(events) {
            //     fetch('/save-calendar-events', {
            //         method: 'GET',
            //         headers: {
            //             'Content-Type': 'application/json',
            //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            //         },
            //         body: JSON.stringify({ events: events })
            //     })
            //         .then(response => response.json())
            //         .then(data => {
            //             console.log('Success:', data);
            //         })
            //         .catch(error => {
            //             console.error('Error:', error);
            //         });
            // }
        });

    </script>

    <style>

        body {
            margin: 40px 10px;
            padding: 0;
            font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
            font-size: 14px;
        }

        #calendar {
            max-width: 1200px;
            margin: 0 auto;
        }

    </style>
</head>
<body>
{{-- <form action="save-calendar-events" method="POST">
    @csrf
    <input type="text" placeholder="title" name="title">
    <button type="submit">Shcduel</button>
</form> --}}
<a href="schedule-events">Make meeting</a>
<br>
<div id='calendar'>

</div>

</body>
</html>
