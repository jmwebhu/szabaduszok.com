var ProjectPartner = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$apply                 = $("a#apply");
        this.$undoApplication       = $("a#undoApplication");
        this.$approveApplication    = $("a.approveApplication");
        this.$rejectApplication     = $("a.rejectApplication");
        this.$cancelParticipation   = $("a.cancelParticipation");

        this.$useShortDescription   = $("div.project-partner-fancybox a#use-short-description");
        this.$cancel                = $("div.project-partner-fancybox button.cancel");
        this.$operation             = $("div.project-partner-fancybox button.operation");

    },
    bindEvents: function () {
        this.$apply.click(ProjectPartner.applyClick);
        this.$undoApplication.click(ProjectPartner.undoApplicationClick);
        this.$approveApplication.click(ProjectPartner.approveApplicationClick);
        this.$rejectApplication.click(ProjectPartner.rejectApplicationClick);
        this.$cancelParticipation.click(ProjectPartner.cancelParticipationClick);

        this.$useShortDescription.click(ProjectPartner.useShortDescriptionClick);
        this.$cancel.click(ProjectPartner.cancelClick);
        this.$operation.click(ProjectPartner.operationClick);
    },
    applyClick: function () {
        ProjectPartner.openFancybox("candidate");
        return false;
    },
    undoApplicationClick: function () {
        ProjectPartner.openFancybox("undo-application");
        return false;
    },
    approveApplicationClick: function () {
        $("input[name='project_partner_id']").val($(this).data("project_partner_id"));
        ProjectPartner.openFancybox("approve-application");
        return false;
    },
    rejectApplicationClick: function () {
        $("input[name='project_partner_id']").val($(this).data("project_partner_id"));
        ProjectPartner.openFancybox("reject-application");
        return false;
    },
    cancelParticipationClick: function () {
        $("input[name='project_partner_id']").val($(this).data("project_partner_id"));
        ProjectPartner.openFancybox("cancel-participation");
        return false;
    },
    openFancybox: function (target) {
        var myOption =  $.extend(true, {}, fancyBoxOptions);
        myOption.href = "#project-partner-fancybox-" + target;
        myOption.height = 500;

        myOption.afterLoad = function () {
            $(".project-partner-fancybox textarea").val("");
            $(".project-partner-fancybox button").prop("disabled", false);
        };

        $.fancybox.open(myOption);
    },
    useShortDescriptionClick: function () {
        $("textarea#message").val(USER.short_description);
        return false;
    },
    cancelClick: function () {
        $.fancybox.close();
        return false;
    },
    operationClick: function () {
        var $this = $(this);

        var operation = OperationFactory.create($this.data("operation"));
        operation.doOperation($this);

        return false;
    }
};

var OperationBase = {
    getOperationLang: function () {},
    getAjaxTarget: function() {},
    getFormName: function () {},
    doOperation: function ($button) {
        var self = this;
        $button.prop("disabled", true);
        var ajax = new AjaxBuilder();

        var beforeSend = function () {
            Default.startLoading($("div.project-partner-fancybox span.loading"));
        };

        var success = function (data) {
            Default.stopLoading(data.error, "Sikeres " + self.getOperationLang(), $("div.project-partner-fancybox span.loading"));
        };

        var error = function () {
            Default.stopLoading(true, "Sikeres " + self.getOperationLang(), $("div.project-partner-fancybox span.loading"));
        };

        var complete = function(data) {
            location.reload();
        };

        ajax.url(ROOT + "projectpartner/ajax/" + self.getAjaxTarget()).data($("form#" + self.getFormName() + "-form").serialize())
            .beforeSend(beforeSend).success(success).complete(complete).error(error).send();
    }
};

var Apply = $.extend(true, {}, OperationBase);
Apply.getOperationLang = function () { return "jelentkezés"; };
Apply.getAjaxTarget = function() { return "apply"; };
Apply.getFormName = function () { return "apply"; };

var UndoApplication = $.extend(true, {}, OperationBase);
UndoApplication.getOperationLang = function () { return "visszavonás"; };
UndoApplication.getAjaxTarget = function() { return "undoApplication"; };
UndoApplication.getFormName = function () { return "undo-application"; };

var ApproveApplication = $.extend(true, {}, OperationBase);
ApproveApplication.getOperationLang = function () { return "jóváhagyás"; };
ApproveApplication.getAjaxTarget = function() { return "approveApplication"; };
ApproveApplication.getFormName = function () { return "approve-application"; };

var RejectApplication = $.extend(true, {}, OperationBase);
RejectApplication.getOperationLang = function () { return "elutasítás"; };
RejectApplication.getAjaxTarget = function() { return "rejectApplication"; };
RejectApplication.getFormName = function () { return "reject-application"; };

var CancelApplication = $.extend(true, {}, OperationBase);
CancelApplication.getOperationLang = function () { return "törlés"; };
CancelApplication.getAjaxTarget = function() { return "cancelParticipation"; };
CancelApplication.getFormName = function () { return "cancel-participation"; };

var OperationFactory = {
    create: function (operation) {
        switch (operation) {
            case "apply":
                return Apply;

            case "undo-application":
                return UndoApplication;

            case "approve-application":
                return ApproveApplication;

            case "reject-application":
                return RejectApplication;

            case "cancel-participation":
                return CancelApplication;
        }
    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
