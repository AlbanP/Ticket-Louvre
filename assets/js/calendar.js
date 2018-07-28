var timeZoneParis = $('.datepicker').data('time-zone');
var today = new Date();
var timeZoneToday = today.getTimezoneOffset();
today.setMinutes(today.getMinutes() + timeZoneToday + (timeZoneParis/60));

// Table of french holidays fixe
var dateNoreservation = [{"day": 1, "month": 0}, {"day" : 8, "month" : 4} ,{"day": 14, "month": 6},{"day": 15, "month": 7},{"day": 11, "month": 10},{"day": 25, "month": 11}];
// Date of Easter and holidays depending (push to noreservation)
var easterDate = new Date($('.datepicker').data('easter-date')*1000);
var mondayEaster = new Date();
mondayEaster.setDate(easterDate.getDate() + 1); //Lundi de Pâques
dateNoreservation.push({"day": mondayEaster.getDate(), "month": mondayEaster.getMonth()});
var ascension = new Date();
ascension.setDate(easterDate.getDate() + 39); //Jeudi de l'Ascension
dateNoreservation.push({"day": ascension.getDate(), "month": ascension.getMonth()});
var pentecote = new Date();
pentecote.setDate(easterDate.getDate() + 50); //Lundi de Pentecôte
dateNoreservation.push({"day": pentecote.getDate(), "month": pentecote.getMonth()});
// Date close Museum Louvre
var dateClose = [{"day": 1, "month": 4}, {"day" : 1, "month" : 10} ,{"day": 5, "month": 11}];
// Date open day and nbVisitor
var dateOpen = $('.datepicker').data('list-days');


$(function(){
    // If date is choose
    var dateVisit = $('#date-choose').data('date-choose');
    if (dateVisit){
        dateVisit = new Date(dateVisit);
        showDate(dateVisit);
    }
    //Display the number of month depending screen width
    var nbMonth = 1;
    function nbCalendar(){
        if ($(window).width() < 673) {
            nbMonth = 1
        } else if ($(window).width() < 960) {
            nbMonth = 2
        } else if ($(window).width() < 1200) {
            nbMonth = 3
        } else {
            nbMonth = 4
        }
        $('#date').removeClass('hasDatepicker');
        $('.ui-datepicker').remove();
        calendar();
    }
    $(window).resize(function(){
        nbCalendar();
    });
    //Init Date Picker
    nbCalendar();
    function calendar() {
        $('.datepicker').datepicker({
            dateFormat: 'dd/mm/yy',
            minDate: '0',
            maxDate: '+6m',
            numberOfMonths: nbMonth,
            beforeShowDay : checkDate,
            onSelect : selectDate
        });
    }
    // Check valid date for all days in calendar
    function checkDate(date) {
        if (date.getDay()== "2" || closeDay(date)){
            return [false, 'close'];
        } else if(date.getDay()== "0" || noreservationDay(date)) {
            return [false, 'noreservation'];
        } else if(fullDay(date)) {
            return [false, 'full'];
        } else if( date.getDate() == today.getDate() && date.getMonth() == today.getMonth() && date.getFullYear() == today.getFullYear() &&today.getHours() > 17) {
            return [false, ''];
        } else {
            return [true, 'valid'];
        }
    }

    function closeDay(date){
        var response = false;
        $.each(dateClose, function(i, d) {
            if (date.getDate() == d.day  && date.getMonth() == d.month) {
                response = true;
                return false;
            }
        });
        return response;
    }
    function noreservationDay(date){
        var response = false;
        $.each(dateNoreservation, function(i, d) {
            if (date.getDate() == d.day  && date.getMonth() == d.month) {
                response = true;
                return false;
            }
        });
        return response;
    }
    // Check if the number vistor is >1000 (return false)
    function fullDay(date){
        var response = false;
        $.each(dateOpen, function(i, d) {
            var open = new Date(d.day.date.slice(0, 10));
            if (date.getDate() == open.getDate()  && date.getMonth() == open.getMonth() && d.nbVisitor >= 1000) {
                response = true;
                return false;
            }
        });
        return response;
    }
    // Display date select, number place and choose half-day or day (if < 14h for today)
    function selectDate(){
        var choose = $(this).datepicker('getDate');
        $('.error-card').css('display', 'none');
        $('#date-select').val(choose.toDateString());
        showDate(choose);
    }
    // Show date and nb places
    function showDate(choose){
        var chooseTimeZone = choose;
        chooseTimeZone.setMinutes(chooseTimeZone.getMinutes() - timeZoneToday);
        var local = $('#local').data('local')
        var options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        $('#date-choose').html(chooseTimeZone.toLocaleDateString(local, options));
        if (dateOpen.length){
            $.each(dateOpen, function(i, d) {
                var day = new Date(d.day.date.slice(0, 10));
                if (choose.getDate() == day.getDate()  && choose.getMonth() == day.getMonth() && choose.getFullYear() == day.getFullYear()){
                    $('#nb-places').html(1000 - d.nbVisitor);
                    return false;
                }
                $('#nb-places').html('1000');
            });
        } else {
            $('#nb-places').html('1000');
        }
        $('#choose').css('display', 'block');
        // 
        if ((choose.getDate() == today.getDate() && choose.getMonth() == today.getMonth() && choose.getFullYear() == today.getFullYear() && today.getHours() > 13)){
            $('#button-day').css('display', 'none');
        } else {
            $('#button-day').css('display', 'inline-block');
        }
        $('#button-half-day').css('display', 'inline-block');
    }
})