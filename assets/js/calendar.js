$( document ).ready(function() 
 {
    var EditingActivityInfo =
    {
        modalAction: undefined,
        activityIndex: 0,
        activityOrder: 0,
        dayId: ""
    };

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
            var date = button.closest(".day").attr('date');
            var time = button.data('time');
            var activity = button.data('activity');

            EditingActivityInfo.activityIndex = button.data('index');
            EditingActivityInfo.activityOrder = button.data('order');
            EditingActivityInfo.dayId = "#" + button.closest(".day").attr("id");

            $(this).find('.modal-body #when').val(date);
            $(this).find('.modal-body #time').val(time);
            $(this).find('.modal-body #activity').val(activity);
            
            if (EditingActivityInfo.activityIndex > 0)
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

    // modal form submit
    $( "#add-activity" ).submit(function( event ) 
    {
        event.preventDefault();

        var whenStr = $("#when").val().trim();

        if (!whenStr)
        {
            return;
        }

        var activity = $("#activity").val().trim();
        var time = $("#time").val().trim();
        var sourceDayId = EditingActivityInfo.dayId;
        var date = dateToString(new Date(whenStr));
        var targetDayId = "#" + $(".day[date='" + date + "']").attr("id");

        if (!EditingActivityInfo.activityIndex)
        {
            EditingActivityInfo.activityIndex = 0;
        }

        if (!activity)
        {
            activity = "???";
        }

        var updateData = 
        {
            "date": whenStr,
            "time": time,
            "activity": activity,
            "targetId": targetDayId
        };

        if (EditingActivityInfo.modalAction === "delete")
        {
            updateData.ordering = [];
            updateData.id = EditingActivityInfo.activityIndex;

            populateOrdering(targetDayId, updateData.ordering, updateData.id);

            ajaxActivity("/calendar/remove", updateData, "An error occurred trying to remove activity. I'm sorry.", "json", function(result)
            {
                if (!result)
                {
                    alert("Something went wrong trying to remove the activity. I'm sorry.");

                    return;
                }

                var sourceActivity = $("#activity-" + result.id);

                sourceActivity.remove();
            });
        }
        else if (EditingActivityInfo.modalAction === "add")
        {
            updateData.order = $(targetDayId + " > .list-group").children().length + 1;

            ajaxActivity("/calendar/add", updateData, "Something went wrong creating activity. I'm sorry.", "json", function(result)
            {
                if (!result)
                {
                    alert("Something went wrong creating activity. I'm sorry.");

                    return;
                }

                var targetListGroup = $(result.targetId + " > .list-group");

                addActivityToListGroup(targetListGroup, result.id, result.time, result.activity, result.order, true);
            });
        }
        else if (EditingActivityInfo.modalAction === "update")
        {
            updateData.id = EditingActivityInfo.activityIndex;

            if (sourceDayId === targetDayId)
            {
                
            }
            else
            {
                updateData.ordering = [];

                populateOrdering(targetDayId, updateData.ordering);

                updateData.ordering.push({ id: updateData.id, order: updateData.ordering.length + 1 });

                populateOrdering(sourceDayId, updateData.ordering, updateData.id);                
            }

            ajaxActivity("/calendar/update", updateData, "Something went wrong updating the activity. I'm sorry.", "json", function(result)
            {
                // result is all the activity that was added
                if (!result)
                {
                    alert("Something went wrong updating the activity. I'm sorry.");

                    return;
                }

                if (result.hasOwnProperty("ordering"))
                {
                    var sourceActivity = $("#activity-" + result.id);

                    sourceActivity.remove();

                    var targetListGroup = $(result.targetId + " > .list-group");

                    addActivityToListGroup(targetListGroup, result.id, result.time, result.activity, targetListGroup.children().length + 1, true);
                }
                else
                {
                    var sourceActivity = $("#activity-" + result.id);

                    sourceActivity.data('time', result.time);
                    sourceActivity.data('activity', result.activity);
                    sourceActivity.html('<span class="label label-primary">' + timeToString(result.time) + '</span> ' + result.activity);
                    sourceActivity.highlight();
                }
            });
        }
        else
        {
            // uh, what the fuck?
        }
    });

    $( "#activity-submit" ).click(function() 
    {
        EditingActivityInfo.modalAction = "add";
        $( "#add-activity" ).submit();
    });

    $( "#activity-delete" ).click(function() 
    {
        EditingActivityInfo.modalAction = "delete";
        $( "#add-activity" ).submit();
    });

    $( "#activity-update" ).click(function() 
    {
        EditingActivityInfo.modalAction = "update";
        $( "#add-activity" ).submit();
    });

    $(".list-group").sortable(
        {
            start: function(event, ui)
            {
                var sourceDayId = ui.item.parent().parent().attr("id");

                ui.item.attr("source-day-id", sourceDayId);
            },
            update: function(event, ui)
            {
                if (this === ui.item.parent()[0]) // only call once for list to list moves
                {
                    var sourceDayId = "#" + ui.item.attr("source-day-id");
                    var targetDayId = "#" + ui.item.parent().parent().attr("id");
                    var updateData = 
                    {
                        "id": ui.item.attr("data-index"),
                        "date": $(targetDayId).attr("date"),
                        "time": ui.item.attr("data-time"),
                        "activity": ui.item.attr("data-activity"),
                        "targetId": targetDayId,
                        "sourceId": sourceDayId,
                        "ordering": []
                    };

                    populateOrdering(targetDayId, updateData.ordering);

                    if (sourceDayId !== targetDayId)
                    {
                        populateOrdering(sourceDayId, updateData.ordering);
                    }

                    ui.item.removeAttr("source-day-id");

                    ajaxActivity("/calendar/update", updateData, "Something went wrong trying reorder the activities. I'm sorry.", "json", function(result)
                    {
                        if (!result)
                        {
                            alert("I'm not sure if that worked. Sorry");

                            return;
                        }

                        $("#activity-" + result.id).highlight();
                    });
                }
            },
            revert: true,
            connectWith: ".list-group"
        }
    ).disableSelection();

    // fetch activities /calendar/get(start, end)
    var dates = calcDates();

    $.ajax(
        {
            url: "/calendar/get",
            success: function(result)
            {
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

function populateOrdering(targetDayId, orderingArray, excludeId)
{
    if (excludeId === undefined)
    {
        excludeId = -1;
    }

    var order = 1;

    $(targetDayId + " > .list-group").children().each(function(index, ele)
    {
        var activityId = $(this).data("index");

        if (activityId !== excludeId)
        {
            orderingArray.push({ id: activityId, order: order });

            ++order;
        }
    });
}

 function timeToString(time)
 {
     var timeStr = "";

    if (time)
    {
        timeStr += "@ " + time;
    }

    return (timeStr);
 }

 function ajaxActivity(url, data, errorTxt, responseType, resultFunction)
 {
        $.ajax( // update in place, no re-ordering necessary
        {
            url: url,
            success: resultFunction,
            error: function()
            {
                alert(errorTxt);
            },
            method: 'POST',
            cache: false,
            data:
            {
                activityData: data
            },
            dataType: responseType
        }
    );
 }

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

function addActivityToListGroup(listGroup, index, time, activity, order, emph = false)
{
    var timeStr = "";
    var addBtn = $(listGroup).parent().find(".add-activity");
    var orderAsInt = parseInt(order);
    
    if (time)
    {
        timeStr += " @ " + time;
    }

    var dataStr = 'id="activity-' + index + '" data-time="' + time + '" data-activity="' + activity + '" data-order="' + orderAsInt + '"';          

    $(listGroup).append('<a href="#" class="list-group-item" data-toggle="modal" data-index="' + index + '" data-target="#activityModal" ' + dataStr + '><span class="label label-primary">' + timeStr + '</span> ' + activity + '</a>');
    
    var addedItem = $(listGroup).find("#activity-" + index);

    if (emph)
    {
        addedItem.highlight();
    }

    addBtn.data("order", orderAsInt + 1);
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
            var dateAsStr = dateToString(dates[x]);

            $(dayId + " > .day-heading").append(dayAbbr + " " + dates[x].getDate());
            $(dayId).attr("date", dateAsStr);

            dayCurrent(dates[x], today, dayId);

            var activities = dataHtml.find(".date[data-value='" + dateAsStr + "']");

            if (activities)
            {
                activities.find(".activity").each(function(index)
                {
                    addActivityToListGroup(listGroup, $(this).data("id"), $(this).data("time"), $(this).data("description"), $(this).data("order"), true);
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

            if (dates[x].getTime() < today.getTime())
            {
                $(dayId).addClass("day-past");
                $(dayId).children().last().hide(); // takes out the add button
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
        }
 }

 function dateToString(date)
 {
     var str = (date.getMonth() + 1) + "/" + date.getDate() + "/" + date.getFullYear();

     return(str);
 }