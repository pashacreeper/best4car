var companiesList = function($, Routing){
    $(document).ready(function(){

        var mapHeight = $('#map').height(),
            footerHeight = $('section.footer').height();

        $('#companiesListContainer').height(mapHeight - footerHeight - 1);
        $('#advancedSearch').height(mapHeight - footerHeight - 75);

        var mapWidth = $('#map').width()
        // Показ компаний списком
        $("#companiesList").hide();
    });
};