<?php
    /* @var $model array */
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
                <button type="button" class="btn <?php if(count($model['clocks']) == 3) echo "btn-secondary"; else echo "btn-outline-secondary";?>">3</button>
            </li>
            <li class="nav-item" onclick="countDisplayClocs(10)">
                <button  type="button" class="btn <?php if(count($model['clocks']) == 10) echo "btn-secondary"; else echo "btn-outline-secondary";?>">10</button>
            </li>
            <li class="nav-item" onclick="countDisplayClocs(30)">
                <button type="button" class="btn <?php if(count($model['clocks']) == 30) echo "btn-secondary"; else echo "btn-outline-secondary";?>">30</button>
            </li>
        </ul>
    </div>
    <?php
    use Main\Clock;
    use Main\ListItem;
    if(isset($model)) {
        foreach ($model['clocks'] as $item) {
            ListItem::render($item->address, $item->type, $item->friendlyDist, "?action=details&id=".$item->id);
        }
    }
    ?>
</div>

<?php if(isset($model['page'])) : ?>
<nav>
    <ul class="pagination justify-content-center">
        <li class="page-item <?php if($model['page'] == 1) echo 'disabled'; ?>">
            <a class="page-link" href="<?= preg_replace('~&page=\d+~', '&page='.($model['page']-1), $_SERVER['REQUEST_URI'])?>" tabindex="-1">Previous</a>
        </li>

        <li class="page-item"><a class="page-link" href="<?php if($model['page'] == 1) echo $_SERVER['REQUEST_URI'].'&page=1">1';
            else echo preg_replace('~&page=\d+~', '&page='.($model['page']-1), $_SERVER['REQUEST_URI']).'">'.($model['page']-1);?></a></li>
        <li class="page-item"><a class="page-link" href="<?php if($model['page'] == 1) echo $_SERVER['REQUEST_URI'].'&page=2">2';
            else echo preg_replace('~&page=\d+~', '&page='.($model['page']), $_SERVER['REQUEST_URI']).'">'.($model['page']);?></a></li>
        <li class="page-item"><a class="page-link" href="<?php if($model['page'] == 1) echo $_SERVER['REQUEST_URI'].'&page=3">3';
            else echo preg_replace('~&page=\d+~', '&page='.($model['page']+1), $_SERVER['REQUEST_URI']).'">'.($model['page']+1);?></a></li>
        <li class="page-item <?php if($model['page'] == $model['totalPages']) echo 'disabled'; ?>">
            <a class="page-link" href="<?= preg_replace('~&page=\d+~', '&page='.($model['page']+1), $_SERVER['REQUEST_URI'])?>">Next</a>
        </li>
    </ul>
</nav>
<?php endif;?>


<button type="button" class="btn btn-primary btn-map" data-bs-toggle="modal" data-bs-target="#mapModal">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-geo-alt-fill" viewBox="0 0 16 16">
        <path d="M8 16s6-5.686 6-10A6 6 0 0 0 2 6c0 4.314 6 10 6 10zm0-7a3 3 0 1 1 0-6 3 3 0 0 1 0 6z"/>
    </svg>
</button>

<div class="modal fade" id="mapModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered modal-fullscreen-lg-down modal-xl">
        <div class="modal-content"  style="height: 80vh" >
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="map" class="" style="img max-width: none; height: 100%;" ></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    ymaps.ready(init);
    function init(){
        var myMap = new ymaps.Map("map", {
                // Координаты центра карты.
                // Порядок по умолчанию: «широта, долгота».
                // Чтобы не определять координаты центра карты вручную,
                // воспользуйтесь инструментом Определение координат.
                center: [<?= (isset($_GET['lat'])) ? $_GET['lat'] : 55.8 ?>, <?= (isset($_GET['lat'])) ? $_GET['long'] : 37.8 ?>],
                // Уровень масштабирования. Допустимые значения:
                // от 0 (весь мир) до 19.
                zoom: 12,
                controls: ['geolocationControl']
            }),
            objectManager = new ymaps.ObjectManager({
                // Чтобы метки начали кластеризоваться, выставляем опцию.
                clusterize: true,
                // ObjectManager принимает те же опции, что и кластеризатор.
                gridSize: 32,
                clusterDisableClickZoom: false,
            });

        myMap.geoObjects.add(objectManager);
        var mapModalEl = document.getElementById('mapModal')
        mapModalEl.addEventListener('shown.bs.modal', function (event) {
            fetch('index.php?action=geoJson')
                .then(response => response.json())
                .then(data => objectManager.add(data));
            myMap.container.fitToViewport();
           // console.log(myMap);
        });
    }
</script>