<?php echo $header; ?>

<style>
    #content {
        border-top: 1px solid #c3c3c3;
        padding: 30px 0 10px;
        height: 530px;
    }
    #map {
        outline: 1px solid #c3c3c3;
        height: 530px;
        width: 700px;
        float: right;
    }
    .gmaps-div {
        background-color: #ffffff;
        background-clip: padding-box;
        border-style: solid;
        border-width: 1px 0 1px 1px;
        border-color: rgba(0, 0, 0, 0.15);
        box-shadow: 0 1px 4px -1px rgba(0, 0, 0, 0.3);
        cursor: pointer;
        text-align: center;
        color: #565656;
    }
    .gmaps-div:hover {
        color: #000000;
        background-color: #ebebeb;
    }
    .gmaps-div.active {
        color: #000000;
    }
    .gmaps-button {
        font-family: Roboto,Arial,sans-serif;
        font-size: 11px;
        padding: 1px 6px;
    }
    #offices-list {
        float: left;
        height: 530px;
        width: 245px;
        margin-right: 10px;
        border-right: 1px solid #c3c3c3;
        color: #313131;
    }
    #offices-list div.office + div.office {
        margin-top: 50px;
    }
    #offices-list div.office + div.parent {
        margin-left: 30px;
        margin-top: 20px;
    }
    #offices-list div .name {
        margin-bottom: 10px;
    }
    #offices-list div .name a {
        font-size: 14px;
        font-weight: bold;
        text-decoration: none;
    }
    #offices-list div .phones,
    #offices-list div .address {
        margin-bottom: 10px;
    }
    #offices-list div .email a {
        text-decoration: none;
    }
    #offices-list div .param {
        width: 40px;
        float: left;
    }
    .gm-style-iw {
        overflow: hidden !important;
        min-height: 60px;
    }
</style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&language=<?php echo $language_code; ?>"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/scrollbar/jquery.mCustomScrollbar.css" />
<script type="text/javascript" src="catalog/view/javascript/scrollbar/jquery.mCustomScrollbar.min.js"></script>
<script type="text/javascript">
    (function($){
        $(window).load(function(){
            $('#offices-list').mCustomScrollbar({
                scrollButtons: {
                    enable: false,
                },
                theme: 'my-theme',
            });
        });
    })(jQuery);
</script>
<script type="text/javascript">
    var geocoder;
    var map;
    var infowindow = new google.maps.InfoWindow({Overflow: 'hidden'});
    var trafficLayer = new google.maps.TrafficLayer();
    var trafficOn = false;
    var marker;
    var image = '/image/marker.png';

    var coordinates = [];
    var contentString = [];
    <?php foreach ($offices AS $office) { ?>
        coordinates['<?php echo $office['office_id'] ?>'] = ['<?php echo $office['longitude'] ?>', '<?php echo $office['latitude'] ?>'];
        contentString['<?php echo $office['office_id'] ?>'] = '<h2><?php echo $office['title'] ?></h2><?php echo $office['description'] ?>';
    <?php } ?>

    function TrafficControl(controlDiv, map) {
        // Позиция блока
        controlDiv.style.margin = '5px -5px 0 0';

        // Настройки кнопки
        var controlUI = document.createElement('div');
        controlUI.className = 'gmaps-div';
        controlUI.title = '<?php echo $text_show_traffic; ?>';
        controlDiv.appendChild(controlUI);

        // Настройки текста в блоке
        var controlText = document.createElement('div');
        controlText.className = 'gmaps-button';
        controlText.innerHTML = '<?php echo $text_traffic; ?>';
        controlUI.appendChild(controlText);

        // Действие
        google.maps.event.addDomListener(controlUI, 'click', function() {
            if (trafficOn == false) {
                trafficLayer.setMap(map);
                $('.gmaps-div').addClass('active');
                trafficOn = true;
            } else {
                trafficLayer.setMap(null);
                $('.gmaps-div').removeClass('active');
                trafficOn = false;
            }
        });
    }

    function initialize() {
        geocoder = new google.maps.Geocoder();
        var mapDiv = document.getElementById('map');
        var city = '<?php echo $offices[0]['office_id'] ?>';
        var latlng = new google.maps.LatLng(coordinates[city][0] , coordinates[city][1]);
        var mapOptions = {
            zoom: 17,
            center: latlng,
            mapTypeId: google.maps.MapTypeId.ROADMAP,
        }
        map = new google.maps.Map(mapDiv, mapOptions);
        var myMarker = new google.maps.Marker({
            position: latlng,
            map: map,
            optimized: false,
            visible: true,
            icon: image
        });

        // Добавляем кнопку
        var trafficControlDiv = document.createElement('div');
        trafficControlDiv.index = 1;
        map.controls[google.maps.ControlPosition.TOP_RIGHT].push(trafficControlDiv);
        var trafficControl = new TrafficControl(trafficControlDiv, map);

        geocoder.geocode({'latLng': latlng}, function(results, status) {
            if (status == google.maps.GeocoderStatus.OK) {
                if (results[1]) {
                    marker = new google.maps.Marker({
                        position: latlng,
                        map: map,
                        optimized: false,
                        visible: true,
                        icon: image
                    });
                    infowindow.setContent(contentString[city]);
                    infowindow.open(map, marker);
                } else {
                    alert('No results found');
                }
            } else {
                alert('Geocoder failed due to: ' + status);
            }
        });
    }

    google.maps.event.addDomListener(window, 'load', initialize);
</script>

<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>

<?php echo $column_left; ?>
<?php echo $column_right; ?>

<div id="content">
    <?php echo $content_top; ?>

    <div id="offices-list">
        <?php foreach ($offices AS $office) { ?>
            <div class="office<?php echo $office['parent']; ?>">
                <div class="name"><a href="javascript:;" id="<?php echo $office['office_id'] ?>"><?php echo $office['name'] ?></a></div>
                <div class="address"><?php echo $office['address'] ?></div>

                <?php if ($office['phone'] || $office['fax']) { ?>
                    <div class="phones">
                        <?php if ($office['phone']) { ?>
                            <div class="param"><?php echo $text_telephone; ?></div><span class="bold"><?php echo $office['phone'] ?></span>
                        <?php } ?>

                        <?php if ($office['phone'] && $office['fax']) { ?>
                            <br>
                        <?php } ?>

                        <?php if ($office['phone']) { ?>
                            <div class="param"><?php echo $text_fax; ?></div><span class="bold"><?php echo $office['fax'] ?></span>
                        <?php } ?>
                    </div>
                <?php } ?>

                <?php if ($office['email']) { ?>
                    <div class="email"><?php echo $text_email; ?> <a href="mailto:<?php echo $office['email'] ?>"><?php echo $office['email'] ?></a></div>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <div id="map"></div>

    <script type="text/javascript">
        $('#offices-list a').click(function() {
            var city = $(this).attr('id');
            var latlng = new google.maps.LatLng(coordinates[city][0] , coordinates[city][1]);
            map.setCenter(latlng);

            // Добавляем кнопку
            var trafficControlDiv = document.createElement('div');
            trafficControlDiv.index = 1;
            var trafficControl = new TrafficControl(trafficControlDiv, map);

            geocoder.geocode({'latLng': latlng}, function(results, status) {
                if (status == google.maps.GeocoderStatus.OK) {
                    if (results[1]) {
                        marker = new google.maps.Marker({
                            position: latlng,
                            map: map,
                            optimized: false,
                            visible: true,
                            icon: image
                        });
                        infowindow.setContent(contentString[city]);
                        infowindow.open(map, marker);
                    } else {
                        alert('No results found');
                    }
                } else {
                    alert('Geocoder failed due to: ' + status);
                }
            });
        });
    </script>

    <?php echo $content_bottom; ?>
</div>
<?php echo $footer; ?>