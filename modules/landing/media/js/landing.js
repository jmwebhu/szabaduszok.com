var Landing = {
    init: function() {
        this.cacheElements();
        this.bindEvents();
        this.addWidgets();               

        this.openLandingPage(this.$landingPage.val());
    },
    cacheElements: function() {
        
        this.$landingPage   = $('input[name="landing-page"]');                   
    },
    bindEvents: function() {        
    },
    addWidgets: function () {    
    },
    /**
     * Megnyitja azt a landing oldalt, aminek akapott parameter a neve
     */
    openLandingPage: function (name) {
        if (name == 'blackfriday' || $(document).width() >= 464) {
            if (name) {
                var $landingPage = $('div#' + name);
                if ($landingPage.length) {

                    Landing.sendAjax(name);
                    Landing.openFancybox(name);
                }
            }
        }
    },
    /**
     * Megnyit egy fancybox -ot. A kapott id -val rendelkezo div tartalmat nyitja meg.
     *
     * @param string name   Landing oldal neve
     */
    openFancybox: function (name) {

        var htmlId = '#' + name;

        var myOption =  $.extend(true, {}, fancyBoxOptions);
        myOption.href = htmlId;

        $.fancybox.open(myOption);
    },
    /**
     * Elkuldi az open ajaxot, ami noveli a szamlalot es logolja a megnyitast
     *
     * @param string name   Landing oldal neve
     */
    sendAjax: function (name) {
        var ajax    = new AjaxBuilder();
        var success = function (data) {
            if (data.error) {
                console.log(data.error);
            }
        };
        
        ajax.data({name: name}).url(ROOT + 'landing/ajax/open').success(success).send();
    }
};

$(document).ready(function() {
    Landing.init();
});
