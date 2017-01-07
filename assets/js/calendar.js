
// save/update/delete to database
// retrieve from database

$( document ).ready(function() 
 {
    var index = 1;
    var modalButtonClicked = undefined;

    $('#activityModal').on('show.bs.modal', function (event)
        {
            var button = $(event.relatedTarget);
            var date = button.data('date');
            var timeFrame = button.data('time-frame');
            var time = button.data('time');
            var activity = button.data('activity');
            var toggle = button.data('index');

            $(this).find('.modal-body #when').val(date);
            $(this).find('.modal-body #time').val(time);
            $(this).find('.modal-body #activity').val(activity);
            $(this).find('.modal-body #update-add').val(toggle);
            
            if (toggle > 0)
            {
                $(this).find('.modal-body #activity-delete').removeClass("hidden");
                $(this).find('.modal-footer #activity-update').removeClass("hidden");
                $(this).find('.modal-footer #activity-submit').addClass("hidden");
            }
            else
            {
                $(this).find('.modal-body #activity-delete').addClass("hidden");
                $(this).find('.modal-footer #activity-update').addClass("hidden");
                $(this).find('.modal-footer #activity-submit').removeClass("hidden");
            }

            var timeFrameRadios = $(this).find('.modal-body #time-frame input[name="frame"]');

            timeFrameRadios.prop("checked", false);
            timeFrameRadios.parent().removeClass("active");

            if (timeFrame !== undefined)
            {
                timeFrameRadios.filter('[value=' + timeFrame + ']').prop("checked", true).parent().addClass("active");
            }          
        });

    var date_input=$('input[name="when"]');
    var container=$('.modal-body form').length > 0 ? $('.modal-body form').parent() : "body";
    
    date_input.datepicker(
    {
        format: 'mm/dd/yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
    }).on('show.bs.modal', function(event)
    {
        event.stopPropagation();
    });

    buildCalendar();

    $( "#add-activity" ).submit(function( event ) 
    {
        event.preventDefault();
        // fish out form data

        var whenStr = $("#when").val().trim();
        var activity = $("#activity").val().trim();
        var time = $("#time").val().trim();
        var timeFrame = $("#time-frame input:checked").val();
        var activityIndex = $("#update-add").val();

        if (!activityIndex)
        {
            activityIndex = 0;
        }

        var sourceActivity = $(".list-group").find('a[data-index=' + activityIndex + ']');

        if (modalButtonClicked === "delete")
        {
            if (sourceActivity)
            {
                sourceActivity.remove();
                // ajax remove from database
            }

            return;
        }

        // setup values

        if (!whenStr)
        {
            return;
        }

        if (!timeFrame)
        {
            timeFrame = "Any";
        }

        var timeStr = (timeFrame === 'Any') ? "" : timeFrame;

        if (time)
        {
            timeStr += " @ " + time;
        }

        if (!activity)
        {
            activity = "???";
        }

        if (modalButtonClicked === "update")
        {
            if (sourceActivity)
            {
                var sourceWhen = sourceActivity.data('date');

                if (sourceWhen !== whenStr)
                {
                    sourceActivity.remove();

                    modalButtonClicked = "add";
                }
            }
        }
        
        var when = new Date(whenStr);
        var listGroupId = "#" + when.getFullYear() + "-" + (when.getMonth() + 1) + "-" + when.getDate();         

        if (modalButtonClicked === "update")
        {
            if (sourceActivity)
            {
                sourceActivity.data('date', whenStr);
                sourceActivity.data('time-frame', timeFrame);
                sourceActivity.data('time', time);
                sourceActivity.data('activity', activity);
                sourceActivity.html('<span class="label label-primary">' + timeStr + '</span> ' + activity);

                // ajax update database
            }
        }
        else
        {
            var dataStr = 'data-date="' + whenStr + '" data-time-frame="' + timeFrame + '" data-time="' + time + '" data-activity="' + activity + '"';          

            $(listGroupId).append('<a href="#" class="list-group-item" data-toggle="modal" data-index="' + index++ + '" data-target="#activityModal" ' + dataStr + '><span class="label label-primary">' + timeStr + '</span> ' + activity + '</a>'); 

            // ajax add to database
        }       
    });

    $( "#activity-submit" ).click(function() 
    {
        modalButtonClicked = "add";
        $( "#add-activity" ).submit();
    });

    $( "#activity-delete" ).click(function() 
    {
        modalButtonClicked = "delete";
        $( "#add-activity" ).submit();
    });

    $( "#activity-update" ).click(function() 
    {
        modalButtonClicked = "update";
        $( "#add-activity" ).submit();
    });

    $(".list-group").sortable();
    $(".list-group").disableSelection();
 });

 function buildCalendar()
 {
        var months = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        var today = new Date();
        var dt = new Date();
        var daysSinceLastMonday = dt.getDay() + 6;

        if (dt.getDay() == 0)
        {
            daysSinceLastMonday += 7;
        }

        dt.setDate(dt.getDate() - daysSinceLastMonday);

        var dates = new Array();
        var x = 0;
        var daysToProcess = 9 * 7; // 9 weeks, 7 days/per week

        while (x < daysToProcess)
        {
            dates[x] = new Date(dt);
            dates[x].setDate(dates[x].getDate() + x);

            ++x;
        }

        x = 0;
        var week = 0;

        while (x < dates.length)
        {
            if (dates[x].getDay() == 1) // 1 = Monday
            {
                var startDateId = "#start-date-" + week;
                var monId = "#mon-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(monId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(startDateId).append(months[dates[x].getMonth()] + " " + dates[x].getDate());
                $(monId + " > .day-heading").append("Mon " + dates[x].getDate());
                $(monId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(monId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(monId).addClass("day-past");
                }

                dayCurrent(dates[x], today, monId);
            }

            if (dates[x].getDay() == 2)
            {
                var tueId = "#tue-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(tueId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(tueId + " > .day-heading").append("Tue " + dates[x].getDate());
                $(tueId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(tueId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(tueId).addClass("day-past");
                }

                dayCurrent(dates[x], today, tueId);
            }

            if (dates[x].getDay() == 3)
            {
                var wedId = "#wed-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(wedId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(wedId + " > .day-heading").append("Wed " + dates[x].getDate());
                $(wedId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(wedId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(wedId).addClass("day-past");
                }

                dayCurrent(dates[x], today, wedId);
            }

            if (dates[x].getDay() == 4)
            {
                var thuId = "#thu-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(thuId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(thuId + " > .day-heading").append("Thu " + dates[x].getDate());
                $(thuId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(thuId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(thuId).addClass("day-past");
                }

                dayCurrent(dates[x], today, thuId);
            }

            if (dates[x].getDay() == 5)
            {
                var friId = "#fri-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(friId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(friId + " > .day-heading").append("Fri " + dates[x].getDate());
                $(friId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(friId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(friId).addClass("day-past");
                }

                dayCurrent(dates[x], today, friId);
            }

            if (dates[x].getDay() == 6)
            {
                var satId = "#sat-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(satId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(satId + " > .day-heading").append("Sat " + dates[x].getDate());
                $(satId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(satId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(satId).addClass("day-past");
                }

                dayCurrent(dates[x], today, satId);
            }

            if (dates[x].getDay() == 0) // 0 = Sunday
            {
                var endDateId = "#end-date-" + week;
                var sunId = "#sun-" + week;

                if (dates[x].getDate() == 1)
                {
                    $(sunId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
                }

                $(endDateId).append(months[dates[x].getMonth()] + " " + dates[x].getDate());     
                $(sunId + " > .day-heading").append("Sun " + dates[x].getDate());
                $(sunId + " > .list-group").attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
                $(sunId + " > .add-activity > .btn").attr("data-date", (dates[x].getMonth() + 1) + "/" + dates[x].getDate() + "/" + dates[x].getFullYear());

                if (dates[x].getTime() < today.getTime())
                {
                    $(sunId).addClass("day-past");
                }

                dayCurrent(dates[x], today, sunId);

                ++week;
            }

            ++x;
        }
 }

 function dayCurrent(date, today, elementId)
 {
     if (date.getYear() == today.getYear() &&
         date.getMonth() == today.getMonth() &&
         date.getDate() == today.getDate())
        {
            $(elementId + " > .day-heading").addClass("day-current");
            $("#add-btn").attr("data-date", (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear());
        }
 }