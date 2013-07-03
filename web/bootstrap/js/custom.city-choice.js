var cityChoiser = function(defaultImage, Routing){
    $(document).ready(function(){
        $('.selectTownDropdown').hide();
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
                var root = $('#country-select');
                root.empty();
                var frst_id = null;
                $.each(countries, function (index, country) {
                    if (index == 0){
                        frst_id = country.id;

                    }
                    root.append('<li class="countrySelectTabItem"><a href="#country-' + country.id  + '" id="a-country-' + country.id  + '"><img src="/bootstrap/img/spbimg.png" alt="'+country.name+'" /></a></li>' );
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

var loadCityList = function(defaultImage, Routing){
    $('#country-select li > a').on("click", function(e) {
        e.preventDefault();
        var cid = this.id.split("-").pop();
        $.getJSON(Routing.generate('api_city_all_by_country', { id: cid }))
        .done(function (json) {
            var coun_area = $('#city-select ul');
            coun_area.empty();
            $.each(json, function (index, city) {
                var img_src = (!city.image) ? defaultImage : "/{{ storage_path }}/company_icon/" + city.image;
                coun_area.append(
                    '<li class="townSelectTabItem">'+
                    '<img src="' + img_src + '" style="width: 17px;height: 17px;">' +
                    '<a href="#" class="city-link towns" data-city-id="' + city.id + '" data-city-name="' + city.name + '">' + city.name  + '</a></li>'
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