var ProjectPartner = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$apply             = $('a#apply');
        this.$undoApplication   = $('a#undoApplication');

        this.$useShortDescription = $('div.project-partner-fancybox a#use-short-description');
        this.$cancel = $('div.project-partner-fancybox a.cancel');
        this.$operation = $('div.project-partner-fancybox a.operation');
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
        /*var $this = $(this);
        $this.prop('disabled', true);
        var ajax = new AjaxBuilder();

        var data = {
            project_id: $this.data('project_id')
        };

        var beforeSend = function () {
            $this.prop('disabled', true);
            Default.startLoading();
        };

        legyen oncomplete
        var success = function(data) {
            Default.stopLoading(data.error, 'Sikeres jelentkezés');
            $this.prop('disabled', false);
        };

        ajax.data(data).url(ROOT + 'projectpartner/ajax/apply').beforeSend(beforeSend).success(success).send();
        
        return false;*/
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
        console.log('ope');

        return false;
    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
