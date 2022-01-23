<?php
/* @var $model Clock */
?>
<div class="container">
    <header class="d-flex justify-content-center py-3">
        <ul class="nav nav-pills">
            <form method="get">
                <input type="hidden" name="lat" id="lat" >
                <input type="hidden" name="long" id="long">
                <li class="nav-item">
                    <button type="button" onclick="navigator.geolocation.getCurrentPosition(process)" class="nav-link active">ближайшие</button>
                </li>
            </form>
        </ul>
    </header>
</div>

<div class="list-group">

    <?php
    use Main\Clock;
    use Main\ListItem;
    if(isset($model)) {

            ListItem::render($model->address, $model->type, $model->friendlyDist);

    }
    ?>
</div>