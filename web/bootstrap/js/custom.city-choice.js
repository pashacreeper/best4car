var selectedCity = '';

var cityChoiser = function(defaultImage, Routing){
    $(document).ready(function(){
        setCurrentCity(Routing)

        $('.townSelect').click(function() {
            $('.selectTownDropdown').toggle();
        });

        $.getJSON(Routing.generate('api_city_selected'))
        .done(function (json) {
            $('#choice-city').text(json.city);
        })
        .fail(function (e) {
            console.log(e.message);
        });

        $('#choice-city').on("click", function(e) {
            e.preventDefault();
            $.getJSON(Routing.generate('api_country_all'))
            .done(function (countries) {
                var root = $('#country-select'),
                    frst_id = null;

                root.empty();
                $.each(countries, function (index, country) {
                    var styleClass = "";
                    if (index == 0){
                        frst_id = country.id;
                        styleClass = " class='selectedCountry'";
                    }
                    root.append('<li class="countrySelectTabItem">' +
                        '<a href="#country-' + country.id  + '" id="a-country-' + country.id  + '"' + styleClass + '>' +
                        '<img src="/storage/images/countries/' + country.icon_name + '" alt="' + country.name + '" style="width:40px;"/>' +
                        '</a></li>' 
                    );
                });

                loadCityList(defaultImage, Routing);
                $('#a-country-' + frst_id).trigger('click');
            })
            .fail(function (e) {
                console.log(e.message);
            });
            
        });

    });
};

var setCurrentCity = function(Routing){
    $.getJSON(Routing.generate('api_city_selected'))
    .done(function (json) {
        selectedCity = json.city;
    });
};

var loadCityList = function(defaultImage, Routing){
    $('#country-select li > a').on("click", function(e) {
        e.preventDefault();
        var cid = this.id.split("-").pop();
        $.getJSON(Routing.generate('api_city_all_by_country', { id: cid }))
        .done(function (json) {
            var coun_area = $('#city-select ul');
            coun_area.empty();
            $.each(json, function (index, city) {
                mark = '';
                selectedClass = ''; 
                if (city.name == selectedCity) {
                    mark = '<span class="mark"></span>';
                    selectedClass = 'activTown';
                }
                var img_src = (!city.icon_name) ? defaultImage : "/storage/images/countries/" + city.icon_name;
                coun_area.append(
                    '<li class="townSelectTabItem">' +
                    '<img src="' + img_src + '" style="width: 25px;height: 30px;">' +
                    '<a href="#" class="city-link towns '+selectedClass+'" data-city-id="' + city.id + '" data-city-name="' + city.name + '">' + city.name  + '</a>' + 
                    mark + '</li>'
                );
            });

            reloadChoicedCity(Routing);
        })
        .fail(function (e) {
            console.log(e.message);
        });
    });  
};

var reloadChoicedCity = function(Routing){
    $('a.city-link').on('click',function(e){
        e.preventDefault();
        $('#choice-city').html($(this).data('city-name'));

        $.getJSON(Routing.generate('api_city_choice', { id: $(this).data('city-id') }))
        .done(function (city) {
            $('.selectTownDropdown').hide();

            $.getJSON(Routing.generate('api_get_companies'))
            .done(function (companies) {
                $('#home_compalies').addClass("hide");
                renderMap(city.gps, companies);
            })
            location.reload();
        })
        .fail(function (json) {
            console.log(json.message);
        });

        return false;
    });
};