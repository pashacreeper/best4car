var myMap;
function renderMap (city, companies) {
    $('#map').empty();
    ymaps.ready(init(city, companies));

    function init (city, companies) {
        myMap = new ymaps.Map("map", {
            center: [city.split(",")[0], city.split(",")[1]],
            zoom: 11,
            behaviors: ["default", "scrollZoom"]
        });

        var myCollection = new ymaps.GeoObjectCollection();
        myMap.myCollection = myCollection;
        var buttonMyPlace = new GeolocationButton({
            data : {
                image : "/bootstrap/img/wifi.png",
                plsImage : "/bootstrap/img/man.png",
                lImage : "/bootstrap/img/loader.gif",
                title : 'Определить мое местоположение'
            },
            geolocationOptions: {
                enableHighAccuracy : true
            }
        },{
            selectOnClick: false
        });

        myMap.controls.add(buttonMyPlace, { top : 20, left : 20 });

        filterDataByZoom(myMap.getZoom(), companies);

        myMap.events.add('actionend', function (evt) {
            filterDataByZoom(myMap.getZoom(), companies);
        });

        $('[data-serarch-form]').change(function () {
            $.ajax({
               type: "GET",
               url: Routing.generate('api_auto_get_companies_with_filter'),
               data: $('#advanced-search-form').serialize(),
               dataType: 'json'
            }).done(function (json) {
                myMap.geoObjects.each(function (geoCollection) {
                    geoCollection.each(function (item) {
                        geoCollection.remove(item);
                    });
                });
                addPlacemarks(json);

                myMap.geoObjects.each(function (geoCollection) {
                    geoCollection.each(function (item) {
                        var rat =  parseFloat( item.properties.get('rating') );
                        if (!showByRate( myMap.getZoom(), rat)) {
                            geoCollection.remove(item);
                        }
                    });
                });

            });
            if(!$('#home_compalies').hasClass("hide")){
                doAjax();
            }
        });

        function addPlacemarks (companies) {
            var sel;
            $('div.listItemCompanyPlain').each(function(){ if($(this).css('display')=='block'){ sel = $(this).attr('id').replace('x-list-company',''); }});

            for (var key in companies) {
                var val = companies[key];

                var iconMap = (val.specialization[0] !== undefined && val.specialization[0].iconNameMap !== undefined) ? "/../{{ storage_path }}/company_icon/"+val.specialization[0].iconNameMap : "{{ asset('bundles/stocontent/images/company.png') }}";
                var iconSize = (sel == val.id)? [60,42]: [40,28];
                var vid = val.id;
                var img;
                if(showByRate (myMap.getZoom(),val.rating)){
                    img = {
                        iconImageHref: iconMap,
                        iconImageSize: iconSize,
                    };
                } else {
                    img = { preset: 'twirl#blackClusterIcons' };
                }
                myPlacemark2 = new ymaps.Placemark(val.gps.split(','), {
                    id: val.id,
                    name: val.name,
                    address: val.address,
                    phone: val.phones,
                    web: val.web,
                    logo: val.logoName,
                    rating: val.rating,
                    specialization_html: val.specialization_template,
                    workingTime_html: val.workingTime_template
                }, img);
                myCollection.add(myPlacemark2);
            }

            myCollection.each(function (ob) {
                ob.events.add('click', function () {
                    (function (obPlacemark) {
                        var listObj = $("#list-company" + obPlacemark.properties.get('id'));

                        if (listObj.length && listObj.is(':visible')){
                            obPlacemark.balloon.open();// WHY Open ????? But it works
                            // $('.listItemCompany').removeClass('listActive');
                            // listObj.addClass('listActive');
                            $('.listItemCompanyPlain').each(function(){
                                $(this).hide();
                                $('#'+$(this).attr('data-correspond') ).show();
                            });
                            $('#'+$(listObj).attr('data-correspond')).show();
                            $(listObj).hide();
                            $('#home_compalies').scrollTo('#'+$(listObj).attr('data-correspond'));
                        }
                    })(ob);
                });
            });

            var myBalloonLayout = ymaps.templateLayoutFactory.createClass(
                '<div class="row-fluid">' +
                    '<div class="span2">' +
                        '<img src="/../{{ storage_path }}/company_logo/$[properties.logo]" class="img-polaroid">' +
                    '</div>' +
                    '<div class="span10">' +
                        '<div class="row-fluid">' +
                            '<div class="span8">' +
                                '<h4><a href="../company/$[properties.id]">$[properties.name]</a></h4>' +
                                '<address style="0">$[properties.address]</address>' +
                            '</div>' +
                            '<div class="span4">' +
                                '<h4><i class="icon-b4c-star"></i>$[properties.rating]</h4>' +
                                '<p>Очень хорошо</p>' +
                            '</div>' +
                        '</div>' +
                        '<hr style="margin: 5px 0;"/>' +
                        '<div class="row-fluid">' +
                            '<div class="span6">' +
                                '$[properties.specialization_html]' +
                            '</div>' +
                            '<div class="span6">' +
                                '<h5>Телефон</h5>' +
                                '<p>$[properties.phone.0.phone]</p>' +
                                '<h5>Время работы</h5>' +
                                '$[properties.workingTime_html]' +
                                '<h5>Сайт</h5>' +
                                '<a href="$[properties.web]">$[properties.web]</a>' +
                            '</div>' +
                        '</div>' +
                    '</div>' +
                '</div>'
            );
            ymaps.layout.storage.add('my#superlayout', myBalloonLayout);
            myCollection.options.set({
                balloonContentBodyLayout:'my#superlayout',
                balloonMinWidth: 600
            });

            myMap.geoObjects.add(myCollection);
        }

        function filterDataByZoom (indexZoom, companies) {
            myMap.geoObjects.each(function (geoCollection) {
                geoCollection.each(function (item) {
                    geoCollection.remove(item);
                });
            });

            addPlacemarks(companies);

            myMap.geoObjects.each(function (geoCollection) {
                geoCollection.each(function (item) {
                    var rat =  parseFloat( item.properties.get('rating') );
                    if (!showByRate( indexZoom, rat)) {
                        // geoCollection.remove(item);
                    }
                });
            });

            $('.listItemCompany').each(function (index, element) { // 02 apply filter 2 List
                var id = element.id.replace('list-company', '');
                var el = $(element);
                var rating = parseFloat(el.find('div.span3 span.rating').text());
                if (showByRate(indexZoom, rating)) {
                    if($('#'+el.attr('data-correspond')).css('display')=='none' ) { el.show(); }
                } else {
                    el.hide();
                    $('#'+el.attr('data-correspond')).hide();
                }
            });
        }

        function showByRate (pZoom, pRating) {
            if (pZoom < 11 && pRating > 9)
                return true;
            if (pZoom == 11 && pRating > 9)
                return true;
            if (pZoom == 12 && pRating > 7)
                return true;
            if (pZoom == 13 && pRating > 5)
                return true;
            if (pZoom == 14 && pRating > 3)
                return true;
            return false;
        }
    }
}