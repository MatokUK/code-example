$(document).ready(function() {
    $('a.js-ajax').on('click', function() {
        var method = $(this).data('method');
        var url = $(this).data('target');
        var spinnerPlaceholder = $(this).find('.js-ajax-spinner');
        var button = $(this);
        var loader = '<div class="loader"></div>';

        $(spinnerPlaceholder).hide();
        $(button).append($(loader));
        $(button).addClass('disabled');

        $.ajax({
            url: url,
            method: method,
            success: function (data) {
                var successText = $(button).data('success-text');
                $(spinnerPlaceholder).show();
                $(button).find('.loader').remove();
                $(button).after(successText);
            }
        });

        return false;
    });

    $('strong.spoiler-show').on('click', function() {
        $(this).parent().find('.spoiler-hidden').css('display', 'block');
    });
});



/*




 <script>
 function trackStat(url){

 var href    = new String(document.location.href);

 if (document.location
 && document.location.protocol
 && document.location.protocol=='https:') {
 url = 'https:' + url;
 } else {
 url = 'http:' + url;
 }

 url = url + encodeURIComponent(href.substring(0,200)) +
 '&_r=' + (new Date()).getTime() +
 '&_r2=' + Math.floor(100000 * Math.random());

 var stat_image = new Image();
 stat_image.src = url;
 }    </script>

 <script>
 setTimeout(function(){ trackStat('//service.sme.sk/corporate-stat/collect/artemis_artcl_log/20510130?img=1&ref=www.sme.sk&href='); }, 2000);
 </script>

 */