<div class="container-fluid">

    <!-- add activity form -->
    <div class="modal fade" id="activityModal" tabindex="-1" role="dialog" aria-labelledby="activityModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="activityModalLabel">Activity Editor</h4>
                </div>
                <div class="modal-body">
                    <form id="add-activity">

                        <div class="form-group">
                            <div class="input-group input-group-lg" id="inputDate">
                                <input class="form-control" id="when" name="when" placeholder="MM/DD/YYYY" type="text"/>
                                <div class="input-group-addon"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></div>
                            </div>
                        </div>

                        <div class="form-group hidden">
                            <div class="input-group input-group-lg" id="inputTime"> 
                                <div class="btn-group" data-toggle="buttons" id="time-frame">
                                    <label class="btn btn-info active">
                                        <input type="radio" name="frame" value="Morning" checked> Morning
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="frame" value="Afternoon"> Afternoon
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="frame" value="Evening"> Evening
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="frame" value="Late"> Late
                                    </label>
                                    <label class="btn btn-info">
                                        <input type="radio" name="frame" value="Any"> Any
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-lg" id="inputTime">
                                <div class="input-group-addon">@</div>
                                <input class="form-control" id="time" name="time" placeholder="Specific Time (optional)" type="text" size="5"/>
                                <div class="input-group-addon"><span class="glyphicon glyphicon-time" aria-hidden="true"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-lg" id="inputDescription">
                                <input class="form-control" id="activity" name="activity" placeholder="Activity/Event/Todo" type="text"/>
                                <div class="input-group-addon"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="input-group input-group-lg" id="deleteActivity">
                                <button type="button" id="activity-delete" class="btn btn-danger" data-dismiss="modal">Delete Activity</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" id="activity-submit" class="btn btn-primary" data-dismiss="modal">Add</button>
                    <button type="button" id="activity-update" class="btn btn-primary hidden" data-dismiss="modal">Update</button>
                </div>
            </div>
        </div>
    </div>
<!--
    <button type="button" class="btn btn-primary" id="add-btn" data-toggle="modal" data-index="0" data-target="#activityModal" data-whatever=""><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Activity</button>
-->

        <!-- weekly view (previous) -->
        <?php for($i = 0; $i < $weeks_to_show; $i++) : ?>
            <div class="row match-my-cols calendar-grid" id="week-<?=$i?>">
                <div class="col-md-6">
                    <div class="row">
                    <?php if ($i == 1) : ?>
                        <div class="col-md-3 activity-week week-current text-center">
                    <?php else : ?>
                        <div class="col-md-3 activity-week text-center">
                    <?php endif; ?>
                            <p id="start-date-<?=$i?>"></p>
                            <p>to</p>
                            <p id="end-date-<?=$i?>"></p>
                        </div>
                        <div class="col-md-3 day" id="mon-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <div class="col-md-3 day" id="tue-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <div class="col-md-3 day" id="wed-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                         </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-3 day" id="thu-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <div class="col-md-3 day" id="fri-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <div class="col-md-3 day" id="sat-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                        </div>
                        <div class="col-md-3 day" id="sun-<?=$i?>" date="">
                            <div class="day-heading"></div>
                            <div class="list-group">
                            </div>
                            <div class="text-center actions">
                                <a href="#" class="add-activity list-group-item text-center" data-toggle="modal" data-target="#activityModal" data-order="1"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
                            </div>
                        </div>
                    </div>      
                </div>
            </div>
        <?php endfor; ?>            

        <!-- dummy row to draw bottom border -->
        <div class="row match-my-cols calendar-grid">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                    <div class="col-md-3"></div>
                </div>      
            </div>
        </div>
    </div>

    <!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="<?=base_url();?>assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.4.1/js/bootstrap-datepicker.min.js"></script>
    <script src="<?=base_url();?>assets/js/calendar.js"></script>
  </body>
</html>