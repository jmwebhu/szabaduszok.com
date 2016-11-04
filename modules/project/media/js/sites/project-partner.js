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
        ProjectPartner.openFancybox('candidate');
        return false;
    },
    undoApplicationClick: function () {
        ProjectPartner.openFancybox('undo-application');
        return false;
    },
    openFancybox: function (target) {
        var myOption =  $.extend(true, {}, fancyBoxOptions);
        myOption.href = '#project-partner-fancybox-' + target;
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
        switch ($this.data('operation')) {
            case 'apply':
                ProjectPartner.apply($this);
                break;

            case 'undo-application':
                ProjectPartner.undoApplication($this);
                break;
        }


        return false;
    },
    apply: function ($button) {
        $button.prop('disabled', true);
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
                $button.prop('disabled', false);
            }, 700);
        };

        ajax.url(ROOT + 'projectpartner/ajax/apply').data($('form#apply-form').serialize())
            .beforeSend(beforeSend).success(success).complete(complete).error(error).send();
    },
    undoApplication: function ($button) {
        $button.prop('disabled', true);
        var ajax = new AjaxBuilder();

        var beforeSend = function () {
            Default.startLoading($('div.project-partner-fancybox span.loading'));
        };

        var success = function (data) {
            Default.stopLoading(data.error, 'Sikeres Visszavonás', $('div.project-partner-fancybox span.loading'));
        };

        var error = function () {
            Default.stopLoading(true, 'Sikeres visszavonás', $('div.project-partner-fancybox span.loading'));
        };

        var complete = function(data) {
            setTimeout(function () {
                $.fancybox.close();
                $button.prop('disabled', false);
            }, 700);
        };

        ajax.url(ROOT + 'projectpartner/ajax/undoApplication').data($('form#undo-application-form').serialize())
            .beforeSend(beforeSend).success(success).complete(complete).error(error).send();
    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
