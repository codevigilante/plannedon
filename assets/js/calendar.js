// retain order of list items when moved around
// allow drag and drop from one day to another
// dissiminate between activity, event, todo
// ability to repeat something for X days/weeks/months
// redesign the activities, make them more noticeable/organized/whatever
// create an iphone app
// create a desktop app

$( document ).ready(function() 
 {
    var index = 1;
    var modalButtonClicked = undefined;
    jQuery.fn.highlight = function () 
    {
        $(this).each(function () 
        {
            var el = $(this);
            $("<div/>")
                .width(el.outerWidth())
                .height(el.outerHeight())
                .css({
                    "position": "absolute",
                    "left": el.offset().left,
                    "top": el.offset().top,
                    "background-color": "#ffff99",
                    "opacity": ".7",
                    "z-index": "9999999"
                }).appendTo('body').fadeOut(1000).queue(function () { $(this).remove(); });
        });
    }

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

        if (!whenStr)
        {
            return;
        }

        if (!timeFrame)
        {
            timeFrame = "Any";
        }

        if (!activity)
        {
            activity = "???";
        }

        if (modalButtonClicked === "delete")
        {
            $.ajax(
                {
                    url: "/calendar/remove",
                    success: function(deletedIndex)
                    {
                        var sourceActivity = $(".list-group").find('a[data-index=' + deletedIndex + ']');

                        if (sourceActivity)
                        {
                            sourceActivity.remove();
                        }
                    },
                    error: function()
                    {
                        alert("Something went wrong. I'm sorry.");
                    },
                    method: 'POST',
                    cache: false,
                    data:
                    {
                        index: activityIndex
                    },
                    dataType: 'text'
                }
            );
        }
        else if (modalButtonClicked === "add")
        {
            $.ajax(
                {
                    url: "/calendar/add",
                    success: function(result)
                    {
                        // result is all the activity that was added
                        if (!result)
                        {
                            alert("Something went wrong creating activity. I'm sorry.");

                            return;
                        }

                        var when = new Date(result.when);
                        var listGroupId = "#" + when.getFullYear() + "-" + (when.getMonth() + 1) + "-" + when.getDate();

                        addActivityToListGroup(listGroupId, result.id, result.when, result.timeframe, result.time, result.activity, true);
                    },
                    error: function()
                    {
                        alert("Something went wrong. I'm sorry.");
                    },
                    method: 'POST',
                    cache: false,
                    data:
                    {
                        when: whenStr,
                        timeframe: timeFrame,
                        time: time,
                        activity: activity
                    },
                    dataType: 'json'
                }
            );
        }
        else if (modalButtonClicked === "update")
        {
            $.ajax(
                {
                    url: "/calendar/update",
                    success: function(result)
                    {
                        // result is all the activity that was added
                        if (!result)
                        {
                            alert("Something went wrong creating activity. I'm sorry.");

                            return;
                        }

                        var sourceActivity = $(".list-group").find('a[data-index=' + result.id + ']');
                        var when = new Date(result.when);
                        var listGroupId = "#" + when.getFullYear() + "-" + (when.getMonth() + 1) + "-" + when.getDate();

                        if (sourceActivity)
                        {
                            var sourceWhen = sourceActivity.data('date');

                            if (sourceWhen !== whenStr)
                            {
                                sourceActivity.remove();

                                addActivityToListGroup(listGroupId, result.id, result.when, result.timeframe, result.time, result.activity, true);
                            }
                            else
                            {
                                var timeStr = (result.timeframe === 'Any') ? "" : result.timeframe;

                                if (result.time)
                                {
                                    timeStr += " @ " + result.time;
                                }

                                sourceActivity.data('date', result.when);
                                sourceActivity.data('time-frame', result.timeframe);
                                sourceActivity.data('time', result.time);
                                sourceActivity.data('activity', result.activity);
                                sourceActivity.html('<span class="label label-primary">' + timeStr + '</span> ' + result.activity);
                                sourceActivity.highlight();
                            }
                        }
                    },
                    error: function()
                    {
                        alert("Something went wrong. I'm sorry.");
                    },
                    method: 'POST',
                    cache: false,
                    data:
                    {
                        when: whenStr,
                        timeframe: timeFrame,
                        time: time,
                        activity: activity,
                        index: activityIndex
                    },
                    dataType: 'json'
                }
            );
        }
        else
        {
            // uh, what the fuck?
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

    // fetch activities /calendar/get(start, end)
    var dates = calcDates();

    $.ajax(
        {
            url: "/calendar/get",
            success: function(result)
            {
                //console.log(result);
                buildCalendar($.parseHTML(result.trim()), dates);               
            },
            error: function()
            {
                alert("Something went wrong trying to load the activities. I'm sorry.");
            },
            method: 'POST',
            cache: false,
            data:
            {
                start: dateToString(dates[0]),
                end: dateToString(dates[dates.length-1])
            },
            dataType: 'html'
        }
    );
 });

function calcDates()
{
    var dt = new Date();
    dt.setHours(0, 0, 0, 0);
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
        dates[x].setHours(0, 0, 0, 0);

        ++x;
    }

    return(dates);
}

function addActivityToListGroup(listGroup, index, when, timeFrame, time, activity, emph = false)
{
    var timeStr = (timeFrame === 'Any') ? "" : timeFrame;

    if (time)
    {
        timeStr += " @ " + time;
    }

    var dataStr = 'data-date="' + when + '" data-time-frame="' + timeFrame + '" data-time="' + time + '" data-activity="' + activity + '"';          

    $(listGroup).append('<a href="#" class="list-group-item" data-toggle="modal" data-index="' + index + '" data-target="#activityModal" ' + dataStr + '><span class="label label-primary">' + timeStr + '</span> ' + activity + '</a>'); 

    if (emph)
    {
        $(listGroup).find(".list-group-item[data-index=" + index + "]").highlight();
    }
}

 function buildCalendar(activityHtml, dates)
 {
        var months = new Array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");
        var dayIdLookup = new Array("#sun-", "#mon-", "#tue-", "#wed-", "#thu-", "#fri-", "#sat-");
        var dayAbbrLookup = new Array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
        var today = new Date();
        var datesLength = dates.length; 
        var dataHtml = $("<html/>").html(activityHtml);  

        today.setHours(0, 0, 0, 0);     

        //console.log(dataHtml.find(".date").data("value"));

        var x = 0;
        var week = 0;

        while (x < datesLength)
        {
            var dayId = dayIdLookup[dates[x].getDay()] + week;
            var dayAbbr = dayAbbrLookup[dates[x]. getDay()];

            if (dates[x].getDate() == 1)
            {
                $(dayId + " > .day-heading").append('<span class="label label-info pull-left">' + months[dates[x].getMonth()] + ' ' + dates[x].getFullYear() + '</span>');
            }

            var listGroup = dayId + " > .list-group";

            $(dayId + " > .day-heading").append(dayAbbr + " " + dates[x].getDate());
            $(listGroup).attr("id", dates[x].getFullYear() + "-" + (dates[x].getMonth() + 1) + "-" + dates[x].getDate());
            $(dayId + " > .add-activity > .btn").attr("data-date",  dateToString(dates[x]));

            if (dates[x].getTime() < today.getTime())
            {
                $(dayId).addClass("day-past");
            }

            dayCurrent(dates[x], today, dayId);

            // if there's any activities for this day, add them here
            var slashDate = dateToString(dates[x]);
            var activities = dataHtml.find(".date[data-value='" + slashDate + "']");

            if (activities)
            {
                activities.find(".activity").each(function(index)
                {
                    addActivityToListGroup(listGroup, $(this).data("id"), slashDate, $(this).data("time-frame"), $(this).data("time"), $(this).data("description"));
                });
            }
            
            if (dates[x].getDay() == 1) // 1 = Monday
            {
                var startDateId = "#start-date-" + week;

                $(startDateId).append(months[dates[x].getMonth()] + " " + dates[x].getDate());
            }

            if (dates[x].getDay() == 0) // 0 = Sunday
            {
                var endDateId = "#end-date-" + week;

                $(endDateId).append(months[dates[x].getMonth()] + " " + dates[x].getDate());     

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

 function dateToString(date)
 {
     var str = (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear();

     return(str);
 }