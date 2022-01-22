<?php
    /* @var $model Clock[] */
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
    <div class="d-flex justify-content-end">
        <ul class="nav">
            <li class="nav-item" onclick="countDisplayClocs(3)">
                <button type="button" class="btn <?php if(count($model) == 3){ echo "btn-secondary";} else{ echo "btn-outline-secondary";}?>">3</button>
            </li>
            <li class="nav-item" onclick="countDisplayClocs(10)">
                <button  type="button" class="btn <?php if(count($model) == 10) echo "btn-secondary"; else echo "btn-outline-secondary";?>">10</button>
            </li>
            <li class="nav-item" onclick="countDisplayClocs(30)">
                <button type="button" class="btn <?php if(count($model) == 30) echo "btn-secondary"; else echo "btn-outline-secondary";?>">30</button>
            </li>
        </ul>
    </div>
    <?php
    use Main\Clock;
    use Main\ListItem;
    if(isset($model)) {
        foreach ($model as $item) {
            ListItem::render($item->address, $item->type, $item->friendlyDist);
        }
    }
    ?>
</div>