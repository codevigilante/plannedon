<html><body>
<div id="<?=$user;?>" data-start="<?=$start;?>" data-end="<?=$end;?>">
<?php while ($datedata = each($payload)) : ?>
    <div class="date" data-value="<?php echo $datedata["key"]; ?>">
        <?php while ($activities = each($datedata["value"])) : ?>
            <div class="activity" data-id="<?=$activities["value"]["id"];?>" data-time="<?=$activities["value"]["time"];?>" data-description="<?=$activities["value"]["description"];?>" data-time-frame="<?=$activities["value"]["time_frame"];?>" data-order="<?=$activities["value"]["rel_order"];?>" />
        <?php endwhile; ?>
    </div>
<?php endwhile; ?>
</div>
</body></html>