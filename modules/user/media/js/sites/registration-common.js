var RegistrationCommonApp = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$next = $('button.next');
        this.$prev = $('button.prev');
    },
    bindEvents: function () {
        this.$next.click(RegistrationCommonApp.navigationButtonClick);
        this.$prev.click(RegistrationCommonApp.navigationButtonClick);
    },
    navigationButtonClick: function () {
        var handler = ClickHandlerFactory.createHandler($(this));
        handler.handle();
    }
};

var ClickHandlerBase = {
    getStep: function () {},
    scrollToStep: function () {
        $('html, body').animate({scrollTop:$('ul.steps').position().bottom}, 10);
    },
    handle: function () {
        var $step = this.getStep();

        if ($step.length === 0) {
            return false;
        }

        $step.trigger('click');
        var content = $step.data('content');

        $('div#' + content + ' input:not([type="checkbox"]):visible:first').focus();
        ClickHandlerBase.scrollToStep();
    }
};

var NextClickHandler = $.extend(true, {}, ClickHandlerBase);
NextClickHandler.getStep = function () { return $('ul.steps li.active').next('li'); };

var PrevClickHandler = $.extend(true, {}, ClickHandlerBase);
PrevClickHandler.getStep = function () { return $('ul.steps li.active').prev('li'); };

var ClickHandlerFactory = {
    createHandler: function ($button) {
        if ($button.hasClass('prev')) {
            return PrevClickHandler;
        }

        return NextClickHandler;
    } 
};

$(document).ready(function () {
    RegistrationCommonApp.init();
});
