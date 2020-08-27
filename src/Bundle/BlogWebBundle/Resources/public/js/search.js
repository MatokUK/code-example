$(document).ready(function() {
    $('a.js-search-form-toggle').on('click', function() {

        $('#search-form').toggle();
        return false;
    });

    $('#search-form form').on('submit', function() {
        var searchTerm = $(this).find('input[type="text"]').val();
        var action = $(this).find('input[type="text"]').data('search-action');

        window.location.href =  action + searchTerm;

        return false;
    });

    $(document).keyup(function(e) {
        if (e.keyCode === 27 && $('#search-form').is(':visible')) { // esc
            $('#search-form').toggle();
        }
    });
});
