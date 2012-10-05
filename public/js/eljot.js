var hoverFix = function(){
    $('a, span').hover(function() {
        $(this).addClass('hover');
    }, function() {
        $(this).removeClass('hover');
    });
};

var attachDatepickers = function(query, options){
    var elements = $(query);
    if(elements.length){
       options = options || {};
       $(elements).datepicker(options);
    }
};

var setSelectAutosubmision = function(query, options){
    var elements = $(query);
    if(elements.length){
       options = options || {};
       elements.bind('change', function(element){
           var form = $(element.currentTarget).parents('form');
           if(form.length){
               form.submit();
           }
       })
    }
}

var setSelectClear = function(buttonQuery, selectQuery, options){
    var buttonElements = $(buttonQuery);
    var selectElements = $(selectQuery);
    
    if(selectElements.length){
        options = options || {};
        
        if(buttonElements.length){
            buttonElements.bind('click', function(element){
                var target = $(element.currentTarget);
                
                var text1 = "";
                selectElements.children("option").filter(function(index, element) {
                    return $(element).text() == text1; 
                }).attr('selected', true);
            });
        }
    }
}

$(function(){
//    var date = new Date();
//    date = date.getFullYear() + "-" + date.get() + "-" + date.getDay();
    hoverFix();
    attachDatepickers("input#date_of_receipt", {"dateFormat": "yy-mm-dd"});
    attachDatepickers("input#date_of_payment", {"dateFormat": "yy-mm-dd"});
//    setSelectAutosubmision("select[name=order_year]");
    
    setSelectClear("button#filter-order_year", "select#export");
    setSelectClear("button#search-button", "select#export");
});