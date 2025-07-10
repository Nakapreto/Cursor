jQuery(document).ready(function(){
    jQuery('[data-toggle="popover"]').popover({
        'trigger': 'hover'
    })

    window.onload = function () {
        const toast = getCookie('toastSPL')? getCookie('toastSPL') : false
        //success, error, warning, info
        const typeToast = getCookie('typeToastSPL')? getCookie('typeToastSPL') : 'success'
        if(toast){
            var notifier = new Notifier({
                default_time: '10000'
            });
            notifier.notify(typeToast, toast);
            document.cookie = "toastSPL=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
            document.cookie = "typeToastSPL=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
        }
    }
})

function getCookie(cname) {
    var name = cname + "=";
    var decodedCookie = decodeURIComponent(document.cookie);
    var ca = decodedCookie.split(';');
    for(var i = 0; i <ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}