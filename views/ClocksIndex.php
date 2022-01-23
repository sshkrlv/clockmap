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
                <button type="button" class="btn <?php if(count($model) == 3) echo "btn-secondary"; else echo "btn-outline-secondary";?>">3</button>
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
            ListItem::render($item->address, $item->type, $item->friendlyDist, "?action=details&id=".$item->id);
        }
    }
    ?>
</div>

<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item disabled">
            <a class="page-link" href="#" tabindex="-1">Previous</a>
        </li>

        <li class="page-item"><a class="page-link" href="<?php if(!isset($_GET['page']) || $_GET['page'] == 1) echo $_SERVER['REQUEST_URI'].'&page=1">1';
                                                                else echo explode("&page=", $_SERVER['REQUEST_URI'])[0].'&page='.($_GET['page']-1).'">'.($_GET['page']-1);?></a></li>
        <li class="page-item"><a class="page-link" href="<?php if(!isset($_GET['page']) || $_GET['page'] == 1) echo $_SERVER['REQUEST_URI'].'&page=2">2';
            else echo explode("&page=", $_SERVER['REQUEST_URI'])[0].'&page='.($_GET['page']).'">'.($_GET['page']);?></a></li>
        <li class="page-item"><a class="page-link" href="<?php if(!isset($_GET['page']) || $_GET['page'] == 1) echo $_SERVER['REQUEST_URI'].'&page=3">3';
            else echo explode("&page=", $_SERVER['REQUEST_URI'])[0].'&page='.($_GET['page']+1).'">'.($_GET['page']+1);?></a></li>
        <li class="page-item">
            <a class="page-link" href="#">Next</a>
        </li>
    </ul>
</nav>