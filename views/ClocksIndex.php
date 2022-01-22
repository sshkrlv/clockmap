
<form method="get">
    <input type="hidden" name="lat" id="lat" >
    <input type="hidden" name="long" id="long">
    <button type="button" onclick="navigator.geolocation.getCurrentPosition(process)" class="btn btn-primary btn-lg">ближайшие</button>
</form>
<div class="list-group">
    <?php
    use Main\ListItem;
    if(isset($model)) {
        foreach ($model as $item) {
            ListItem::render($item->address, $item->type, $item->friendlyDist);
        }
    }
    ?>
</div>