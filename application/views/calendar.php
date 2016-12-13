<div class="container-fluid">
    <button type="button" class="btn btn-success"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Add Activity</button>
        <?php
            $week = 1;
            $current = TRUE;
            $days = array("Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat");
            $months = array("", "Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec");

            while ($week <= $weeks)
            {
                if ($week != 1)
                {
                    $current = FALSE;
                }

                $day_idx = ($week - 1) * 7;
                $week_data = array($dates[$day_idx],
                                    $dates[$day_idx + 1],
                                    $dates[$day_idx + 2],
                                    $dates[$day_idx + 3],
                                    $dates[$day_idx + 4],
                                    $dates[$day_idx + 5],
                                    $dates[$day_idx + 6]);
                $current_week = ($week == 1) ? "week-current" : "";
        ?>
        <div class="row match-my-cols calendar-grid" id="week-<?=$week;?>">
            <div class="col-md-6">
                <div class="row">
                    <?php foreach($week_data as $day) : ?>            
                    <?php if ($day["wday"] == 1) : ?>
                    <div class="col-md-3 activity-week <?=$current_week;?> text-center" id="start-end">
                        <p><?php echo $months[$day["mon"]] . " " . $day["mday"]; ?></p>
                        <p>to</p>
                        <p><?php echo $months[$week_data[6]["mon"]] . " " . $week_data[6]["mday"]; ?></p>
                    </div>
                    <?php endif; ?>
                    <?php if ($day[0] < strtotime("today")) : ?>
                    <div class="col-md-3 day-past" id="<?=$day["weekday"]."-".$week;?>">
                    <?php else : ?>
                    <div class="col-md-3" id="<?=$day["weekday"]."-".$week;?>">
                    <?php endif; ?>                        
                        <?php if ($day[0] == strtotime("today")) : ?>
                        <div class="day-heading day-current">        
                        <?php else : ?>
                        <div class="day-heading"> 
                        <?php endif; ?>                
                            <?php if ($day["mday"] == 1) : ?>
                                <span class="label label-info pull-left"><?php echo $months[$day["mon"]] . " " . $day["year"]; ?></span>
                            <?php endif; ?>
                            <?php echo $days[$day["wday"]] . " " . $day["mday"]; ?>
                        </div>
                        <div class="list-group">
                        <!--
                            <a href="#" class="list-group-item"><span class="label label-primary">AM</span> This is an activity that takes up a lot of space</a>
                            <a href="#" class="list-group-item"><span class="label label-primary">1200</span> Gym - squats</a>
                        -->                            
                        </div>
                        <div class="activity text-center"><button type="button" class="btn btn-default btn-xs" aria-label="Left Align"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></button></div>
                    </div>
                    <?php if ($day["wday"] == 3) : ?>
                </div>
            </div>
            <div class="col-md-6">
                <div class="row">
                    <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php                
                $week++;
            } // end while ($week ...)
        ?>
</div>

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?=base_url();?>assets/bootstrap-3.3.7-dist/js/bootstrap.min.js"></script>
</body>
</html>