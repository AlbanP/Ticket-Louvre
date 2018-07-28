require('../css/app.scss');

$(document).ready(function() {
    //Adaptation height content
    function heightContent(){
        var hWindow = $(window).outerHeight();
        var hHeader = $('header').outerHeight();
        var hFooter = $('footer').outerHeight();
        var hContent = hWindow - (hHeader + hFooter);
        $('#content').css('min-height', hContent);
    }
    heightContent();
    $(window).resize(function(){ heightContent()});
})