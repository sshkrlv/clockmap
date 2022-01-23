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
    <div id="map" class="" style="width: auto; max-width: 460px; height: 360px; img max-width: none;" ></div>

</div>

<div id="map" class="" style="width: auto; max-width: 460px; height: 360px; img max-width: none;" ></div>
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