<?php
use Main\Clock;
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

    <div class="card" style="width: auto;">
        <h5 class="card-header text-center"><?= $model->type?></h5>
        <div id="map" class="card-img-top" style="width: auto; max-width: 460px; height: 360px; img max-width: none;" ></div>
        <div class="card-body">
            <p class="card-text"> <?= $model->address?></p>
            <?php if( $model->friendlyDist != null) : ?>
                <p class="card-text"><small class="text-muted"><?= $model->friendlyDist ?></small></p>
            <?php endif; ?>
        </div>
    </div>
</div>


<script type="text/javascript">
    // Функция ymaps.ready() будет вызвана, когда
    // загрузятся все компоненты API, а также когда будет готово DOM-дерево.
    ymaps.ready(init);
    function init(){
        console.log('initializing..');
        // Создание карты.
        var myMap = new ymaps.Map("map", {
            // Координаты центра карты.
            // Порядок по умолчанию: «широта, долгота».
            // Чтобы не определять координаты центра карты вручную,
            // воспользуйтесь инструментом Определение координат.
            center: [<?= $model->coordX ?>, <?= $model->coordY ?>],
            // Уровень масштабирования. Допустимые значения:
            // от 0 (весь мир) до 19.
            zoom: 12,
            controls: ['geolocationControl']
        });
        console.log(myMap);
        var myPlacemark = new ymaps.Placemark([<?= $model->coordX ?>, <?= $model->coordY ?>]);
        myMap.geoObjects.add(myPlacemark);
    }
</script>