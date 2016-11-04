var ProjectPartner = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$apply             = $('a#apply');
        this.$undoApplication   = $('a#undoApplication');

        this.$useShortDescription = $('div.project-partner-fancybox a#use-short-description');
        this.$cancel = $('div.project-partner-fancybox button.cancel');
        this.$operation = $('div.project-partner-fancybox button.operation');
        this.$operationForm = $('div.project-partner-fancybox form#operation-form');
    },
    bindEvents: function () {
        this.$apply.click(ProjectPartner.applyClick);
        this.$undoApplication.click(ProjectPartner.undoApplicationClick);

        this.$useShortDescription.click(ProjectPartner.useShortDescriptionClick);
        this.$cancel.click(ProjectPartner.cancelClick);
        this.$operation.click(ProjectPartner.operationClick);
    },
    applyClick: function () {
        ProjectPartner.openFancybox();
        return false;
    },
    undoApplicationClick: function () {

    },
    openFancybox: function () {
        var myOption =  $.extend(true, {}, fancyBoxOptions);
        myOption.href = '#project-partner-fancybox-candidate';
        myOption.height = 500;

        myOption.afterLoad = function () {
            $('.project-partner-fancybox textarea').val('');
        };

        $.fancybox.open(myOption);
    },
    useShortDescriptionClick: function () {
        $('textarea#message').val(USER.short_description);
        return false;
    },
    cancelClick: function () {
        $.fancybox.close();
        return false;
    },
    operationClick: function () {
        var $this = $(this);
        $this.prop('disabled', true);
        var ajax = new AjaxBuilder();

        var beforeSend = function () {
            Default.startLoading($('div.project-partner-fancybox span.loading'));
        };

        var success = function (data) {
            Default.stopLoading(data.error, 'Sikeres jelentkezés', $('div.project-partner-fancybox span.loading'));
        };

        var error = function () {
            Default.stopLoading(true, 'Sikeres jelentkezés', $('div.project-partner-fancybox span.loading'));
        };

        var complete = function(data) {
            setTimeout(function () {
                $.fancybox.close();
                $this.prop('disabled', false);
            }, 700);
        };

        ajax.url(ROOT + 'projectpartner/ajax/apply').data(ProjectPartner.$operationForm.serialize())
            .beforeSend(beforeSend).success(success).complete(complete).error(error).send();

        return false;
    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
