var application = {};

application.onCountrySelectChange = function(element){
    var countryName = $(element.currentTarget).val()
    $.ajax({
        type: "POST",
        url: "/home/get-cities?format=json",
        data: { 
            "country": countryName
        },
        dataType: "html"
    }).success(function(data, textStatus, request) {
        if(textStatus == "success"){
            console.log(arguments);
            
        }
    });
}