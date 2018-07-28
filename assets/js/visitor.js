$(document).ready(function() {
    
    var local = $('#local').data('local');
    var path = $('.visitor-select').data('path');
    var $container = $('div#app_ticket_visitors');
    var index = $container.find(':input').length;
    var deleteBtn = $('.visitor-select').data('delete');
    var priceBtn = $('.visitor-select').data('price');
    var nbVisitorDay = parseInt($('#nb-visitor-day').text());
    var nbVisitor = $('#app_ticket_visitors').children().length;

    $('.birthday').each(function(){
        calculPrice($(this).attr('id'));
    });

    $('#app_ticket_visitors').on('change', 'input[type="checkbox"]', function() {
        calculPrice($(this).attr('id'));  
    });   
    
    $('#app_ticket_visitors').on('focusin', 'input[type="date"]', function() {
        $('#app_ticket_pay').css('display', 'none');
        $('#btn-calcul').css('display', 'inline');
    });

    $('#app_ticket_visitors').on('focusout', 'input[type="date"]', function() {
        calculPrice($(this).attr('id'));
        $('#btn-calcul').css('display', 'none');
        $('#app_ticket_pay').css('display', 'inline');
    });

    $('#add_visitor').on('click', function(e) {
        addVisitor($container);
        $('.visitor-add').css('display', 'none');
        e.preventDefault();
        return false;
    });

    if (index == 0) {
        addVisitor($container);
    } else {
        $container.children('div').each(function() {
            addWidget($(this));
        });
    }

    function addVisitor($container)
    {
        var template = $container.attr('data-prototype').replace(/__name__/g, index);
        var $prototype = $(template);

        nbVisitorDay = nbVisitorDay - 1;
        $('#nb-visitor-day').text(nbVisitorDay);

        addWidget($prototype);
        $container.append($prototype);
        //
        var prototypeId = $prototype.children(":first").attr('id');
        var visitorPrev = $('#' + prototypeId).parent().prev();
        var namePrev = visitorPrev.find('.name').val();
        var countryPrev = visitorPrev.find('.country option:selected').val();
        $('#' + prototypeId).find('.name').val(namePrev);
        $('#' + prototypeId).find('.country').val(countryPrev);
        displayDeleteBtn();

        index++;
    }

    function addWidget($prototype)
    {
        var $priceIndicator = $("<div class='visitor-price'>" + priceBtn + "<span class='price'></span> €</div>");
        $prototype.children().append($priceIndicator);
        
        var $deleteLink = $("<div class='visitor-delete'><a href='#' class='btn btn-delete'>" + deleteBtn + "</a></div>");
        $prototype.children().append($deleteLink);

        $deleteLink.click(function(e) {
            $prototype.remove();
            e.preventDefault();
            priceTotal();
            
            displayDeleteBtn();

            nbVisitorDay = nbVisitorDay + 1;
            $('#nb-visitor-day').text(nbVisitorDay);
            displayAddBtn();

            return false;
        });
    }

    function calculPrice(id)
    {
        var ticket = id.split('_');
        var pathVisitor = '#app_ticket_visitors_' + ticket[3];
        var reduction = $(pathVisitor + '_reduction:checked').val();
        if (reduction == undefined) {reduction = 0;}
        var birthday = $(pathVisitor + '_birthday').val();
        birthday = birthday.replace( /\//g, '-');
        var dateVisit = $('.date-day').data('date-visit');

        $.ajax({
            url : path,
            method: "POST",
            data: {
            birthday: birthday,
            reduction: reduction,
            dateVisit: dateVisit,
            nbVisitor : nbVisitor
            }
        }).done(function(data){
            $(pathVisitor + '_birthday').parent().find('ul').remove();
            if ($(pathVisitor + '_birthday').val() != ''){
                var birthday = new Date(data.birthday);
                $(pathVisitor + '_birthday').val(birthday.getFullYear() + '-' + digit2(birthday.getMonth() +1 ) + '-' + digit2(birthday.getDate()));
                displayAddBtn();
                $('#nb-visitor-day').text(data.nbVisitorsDay);
            }
            var pathPrice = pathVisitor + ' .price';
            $(pathPrice).text(data.price/100);
            priceTotal();
        }).fail(function(){
            if (local == 'fr') {
                $(pathVisitor + '_birthday').before("<ul><li>Mauvaise date (ex: 14-07-1789)</li><ul>");
            } else {
                $(pathVisitor + '_birthday').before("<ul><li>Bad date (ex: 1789-07-14)</li><ul>");
            }
            $(pathVisitor + ' .price').text('');
        })
    }

    function priceTotal()
    {
        var totalPrice = 0;
        $('.price').each(function() {
            if($(this).text()) {
                totalPrice += parseInt($(this).text());
            }
        });
        $('#total_price').text(totalPrice);
    }

    function displayAddBtn()
    {
        if (nbVisitorDay > 0) {
            $('.visitor-add').css('display', 'block');
        } else {
            $('.visitor-add').css('display', 'none');
        }
    }

    function displayDeleteBtn()
    {
        nbVisitor = $('#app_ticket_visitors').children().length;
        if (nbVisitor > 1) {
            $('.visitor-delete').css('display', 'block');
        } else {
            $('.visitor-delete').css('display', 'none');
        }
    }

    function digit2(number)
    {
        return (number < 10 ? '0' : '') + number
    }
  });