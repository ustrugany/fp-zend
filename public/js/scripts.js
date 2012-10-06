var application = {};

application.onCountrySelectSelect = function(){
    
    var $countrySelect = $('select[name=country]');
    var countryName = $countrySelect.val()
    
    application.blockUI('aside');
    application.blockUI('#view-content');
    
    $.ajax({
        type: "POST",
        url: "/home/get-cities?format=json",
        data: { 
            "country": countryName
        },
        dataType: "html"
    }).success(function(data, textStatus, request) {
        if(textStatus == "success"){
            $('#view-content').html('Wybierz miasto i wciśnij przycisk odśwież');
            $('#city-elements-wrapper').html(data);
        }
    }).complete(function(){
        application.unblockUI('#view-content');
        application.unblockUI('aside');
    });
};

application.onCityWeatherButtonClick = function(){
    
    application.blockUI('aside');
    application.blockUI('#view-content');

    var $countrySelect = $('select[name=country]');
    var countryName = $countrySelect.val()
    var $citySelect = $('select[name=city]');
    var cityName = $citySelect.val()
    
    $.ajax({
        type: "POST",
        url: "/home/get-city-weather?format=json",
        data: { 
            "country": countryName,
            "city": cityName
        },
        dataType: "html"
    }).success(function(data, textStatus, request) {
        if(textStatus == "success"){
            $('#view-content').html(data);
        }
    }).complete(function(){
        application.unblockUI('#view-content');
        application.unblockUI('aside');
    });
    
    return false;
}

application.blockUI = function(query){
    $(query).block({ 
        message: '<span class="busy">Przetwarzanie...</span>', 
        css: { 
                width: '80%', 
                'font-size': '18px',
                border: 'none', 
                padding: '5px', 
                backgroundColor: '#000', 
                '-webkit-border-radius': '10px', 
                '-moz-border-radius': '10px', 
                opacity: .6, 
                color: '#fff' 
            } 
    });
};

application.unblockUI = function(query){
    $(query).unblock();
}