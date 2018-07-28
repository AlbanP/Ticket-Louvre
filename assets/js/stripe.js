$(document).ready(function() {
    Stripe.setPublishableKey('pk_test_8SXgivc5Eu0dJWZmEmFwGomw');
    var $form = $('#payment_form');
    $form.submit(function(e) {
        $('.error-card').remove();
        showModal()
        e.preventDefault()
        $form.find('.button').attr('disable', true)
        Stripe.card.createToken($form, function(status, response) {
            if (response.error) {
                $form.find('.message').remove();
                errorMessage(response);
                //$form.prepend('<div class="error-card"><p>' + response.error.message + '</p></div>');
            } else {
                var token = response.id
                $form.append($('<input type="hidden" name="stripeToken">').val(token));
                $form.get(0).submit();
            }
            $('#modal').css('display', 'none');
        })
    })

    function errorMessage(response) {
        var local = $('#local').data('local');
        var errorMessagesFr = {
            incorrect_number: "Le numéro de carte est incorrect.",
            invalid_number: "Le numéro de carte n’est pas un numéro de carte valide.",
            invalid_expiry_month: "Le mois d’expiration de la carte n’est pas valide.",
            invalid_expiry_year: "L’année d’expiration de la carte n’est pas valide.",
            invalid_cvc: "Le code de sécurité de la carte n’est pas valide.",
            expired_card: "La carte a expiré.",
            incorrect_cvc: "Le code de sécurité de la carte est incorrect.",
            incorrect_zip: "La validation du code postal de la carte a échoué.",
            card_declined: "La carte a été refusée.",
            missing: "Aucun client associé à cette carte",
            processing_error: "Une erreur est survenue lors du contrôle de la carte.",
            rate_limit: "Une erreur est survenue en raison d'un trop grand nombre de requêtes vers le serveur. Veuillez nous contacter si vous rencontrez cette erreur systématiquement."
        };
        if (local == 'fr') {
            error = errorMessagesFr[response.error.code];
        } else {
            error = response.error.message;
        }
        $form.prepend('<div class="error-card"><p>' + error + '</p></div>');
    }

    function showModal() {
        var winH = $(window).outerHeight();
        var winW = $(window).outerWidth();
        var modal = $('#modal');
        modal.css('top', winH/2 - modal.outerHeight()/2);
        modal.css('left', winW/2 - modal.outerWidth()/2);
        modal.fadeIn(100);
    }
})
