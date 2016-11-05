var ProjectPartner = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$apply             = $('a#apply');
        this.$undoApplication   = $('a#undoApplication');
        this.$approveApplication   = $('a.approveApplication');
        this.$approveApplication   = $('a.rejectApplication');

        this.$useShortDescription = $('div.project-partner-fancybox a#use-short-description');
        this.$cancel = $('div.project-partner-fancybox button.cancel');
        this.$operation = $('div.project-partner-fancybox button.operation');

    },
    bindEvents: function () {
        this.$apply.click(ProjectPartner.applyClick);
        this.$undoApplication.click(ProjectPartner.undoApplicationClick);
        this.$approveApplication.click(ProjectPartner.approveApplicationClick);

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
    approveApplicationClick: function () {
        $('#approve-application-form input[name="project_partner_id"]').val($(this).data('project_partner_id'));
        ProjectPartner.openFancybox('approve-application');
        return false;
    },
    rejectApplicationClick: function () {
        $('#reject-application-form input[name="project_partner_id"]').val($(this).data('project_partner_id'));
        ProjectPartner.openFancybox('reject-application');
        return false;
    },
    openFancybox: function (target) {
        var myOption =  $.extend(true, {}, fancyBoxOptions);
        myOption.href = '#project-partner-fancybox-' + target;
        myOption.height = 500;

        myOption.afterLoad = function () {
            $('.project-partner-fancybox textarea').val('');
            $('.project-partner-fancybox button').prop('disabled', false);
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
        var operationLang = '';
        var ajaxTarget = '';
        var formName = '';

        switch ($this.data('operation')) {
            case 'apply':
                operationLang = 'jelentkezés';
                ajaxTarget = 'apply';
                formName = 'apply';
                break;

            case 'undo-application':
                operationLang = 'visszavonás';
                ajaxTarget = 'undoApplication';
                formName = 'undo-application';
                break;

            case 'approve-application':
                operationLang = 'jóváhagyás';
                ajaxTarget = 'approveApplication';
                formName = 'approve-application';
                break;

            case 'reject-application':
                operationLang = 'elutasítás';
                ajaxTarget = 'rejectApplication';
                formName = 'reject-application';
                break;
        }

        ProjectPartner.doOperation(operationLang, ajaxTarget, formName, $this);

        return false;
    },
    doOperation: function (operationLang, ajaxTarget, formName, $button) {
        $button.prop('disabled', true);
        var ajax = new AjaxBuilder();

        var beforeSend = function () {
            Default.startLoading($('div.project-partner-fancybox span.loading'));
        };

        var success = function (data) {
            Default.stopLoading(data.error, 'Sikeres ' + operationLang, $('div.project-partner-fancybox span.loading'));
        };

        var error = function () {
            Default.stopLoading(true, 'Sikeres ' + operationLang, $('div.project-partner-fancybox span.loading'));
        };

        var complete = function(data) {
            location.reload();
        };

        ajax.url(ROOT + 'projectpartner/ajax/' + ajaxTarget).data($('form#' + formName + '-form').serialize())
            .beforeSend(beforeSend).success(success).complete(complete).error(error).send();
    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
