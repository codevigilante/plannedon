$( document ).ready(function()
{
    var Configurables =
    {
        addURL: "https://5tpz2kc3r9.execute-api.us-west-1.amazonaws.com/dev/calendar/add",
        getURL: "https://5tpz2kc3r9.execute-api.us-west-1.amazonaws.com/dev/calendar/get",
        updateURL: "https://5tpz2kc3r9.execute-api.us-west-1.amazonaws.com/dev/calendar/update",
        crossDomain: true
    };
    var displayNone = "d-none";

    // ActivityEditor show
    $('#activityEditorModal').on('show.bs.modal', function (event) 
    {
        var clickThing = $(event.relatedTarget); // which thing triggered the event
        var modal = $(this);
        var date = clickThing.closest("div.card").attr("id");
        var isActivity = clickThing.hasClass("activity");
        var unlockDate = clickThing.hasClass("unlock-date");
        var timeTag = "", activityTag = "", activityText = "", notes = "";

        clickThing.one('focus', function(e){$(this).blur();});
        $('#editor-date').val(date);

        if (unlockDate)
        {
            $('#editor-date').removeAttr("readonly");
        }
        else
        {
            $('#editor-date').attr("readonly", "");
        }

        if (isActivity)
        {
            modal.find("#add-activity").addClass(displayNone);
            modal.find("#delete-activity").removeClass(displayNone);
            modal.find("#update-activity").removeClass(displayNone);

            timeTag = clickThing.find(".time-tag").text();
            activityTag = clickThing.find(".activity-tag").text();
            activityText = clickThing.find(".activity-text").text();
            notes = clickThing.find(".activity-notes").text();
        }
        else
        {
            $("#add-activity").removeClass(displayNone);
            $("#delete-activity").addClass(displayNone);
            $("#update-activity").addClass(displayNone);
        }

        $('#editor-time-tag').val(timeTag);
        $('#editor-activity').val(activityText);
        $('#editor-activity-tag').val(activityTag);
        $('#editor-activity-notes').val(notes);
    });

    // boring tooltips
    $('[data-tooltip="show"]').tooltip();

    // DatePicker thing stuff
    var dateInput = $('input[name="when"]');
    var container = $('.modal-body form').length > 0 ? $('.modal-body form').parent() : "body";
    
    dateInput.datepicker(
    {
        format: 'm-d-yyyy',
        container: container,
        todayHighlight: true,
        autoclose: true,
        assumeNearbyYear: true,
        forceParse: false // actually prevents the input from clearing if no date selected
    }).on('show.bs.modal', function(event)
    {
        event.stopPropagation();
    });

    // activity operations
    $("#activity-editor").submit(function(event)
    {
        event.preventDefault();        
    });

    $("#add-activity").click(function()
    {
        var activityData = getActitvityData();

        $.ajax(
        {
            url: Configurables.addURL,
            success: function(result)
            {
                var payload = $.parseHTML(result.trim());
                var selector = "#" + activityData.date + " .activities";

                $(selector).append(payload);
                $(payload).find(".activity").addBack(".activity").effect("highlight", "slow");
            },
            error: function()
            {
                alert("Something went wrong trying to add activity. Try again?");
            },
            method: 'POST',
            data:
            {
                date: activityData.date,
                timeTag: activityData.timeTag,
                activity: activityData.activity,
                tag: activityData.tag,
                notes: activityData.notes
            },
            dataType: 'html',
            crossDomain: Configurables.crossDomain
        });

        unfocus("*");
    });

    $("#update-activity").click(function()
    {
        var activityData = getActitvityData();

        $.ajax(
        {
            url: Configurables.updateURL,
            success: function(result)
            {
                console.log("update happened");
            },
            error: function()
            {
                alert("Something went wrong trying to update activity. Try again?");
            },
            method: "POST",
            data:
            {
                date: activityData.date,
                timeTag: activityData.timeTag,
                activity: activityData.activity,
                tag: activityData.tag,
                notes: activityData.notes
            },
            crossDomain: Configurables.crossDomain
        });

        unfocus("*");
    });

    $("#activity-tags .tag-name").click(function()
    {
        var selectedTag = $(this).text();
        
        $("#editor-activity-tag").val(selectedTag);
    });

    var today = new Date();
    var todayStr = (today.getMonth() + 1) + "/" + today.getDate() + "/" + today.getFullYear();

    // load activities
    $.ajax(
    {
        url: Configurables.getURL,
        success: function(result)
        {
            var payload = $.parseHTML(result.trim());

            $("#current-calendar").append(payload);       

            attachSortable(".list-group");
        },
        error: function()
        {
            alert("Something went wrong trying to load the activities. Try again?");
        },
        method: 'POST',
        data:
        {
            start: todayStr
        },
        dataType: 'html',
        crossDomain: Configurables.crossDomain
    });
});

function attachSortable(targetElement)
{
    $(targetElement).sortable(
        {
            connectWith: ".list-group",
            placeholder: "activity-placeholder",
            update: function(event, ui)
            {
                if (this === ui.item.parent()[0])
                {
                    ui.item.effect("highlight", "slow");
                }
            }
        }
    ).disableSelection();
}

function getActitvityData()
{
    var activityData =
    {
        date: $('#editor-date').val(),
        timeTag: $('#editor-time-tag').val(),
        activity: $('#editor-activity').val(),
        tag: $('#editor-activity-tag').val(),
        notes: $('#editor-activity-notes').val()
    };
    
    return(activityData);
}

function unfocus(target)
{
    $(target).blur();
}