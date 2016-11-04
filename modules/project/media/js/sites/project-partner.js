var ProjectPartner = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$apply             = $('a#apply');
        this.$undoApplication   = $('a#undoApplication');

        this.$useShortDescription = $('a#use-short-description');
    },
    bindEvents: function () {
        this.$apply.click(ProjectPartner.applyClick);
        this.$undoApplication.click(ProjectPartner.undoApplicationClick);

        this.$useShortDescription.click(ProjectPartner.useShortDescriptionClick);
    },
    applyClick: function () {
        ProjectPartner.openFancybox('Jelentkezés');
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
    openFancybox: function (title) {
        var myOption =  $.extend(true, {}, fancyBoxOptions);
        myOption.href = '#project-partner-fancybox-candidate';
        myOption.height = 500;

        $.fancybox.open(myOption);
    },
    useShortDescriptionClick: function () {
        $('textarea#message').val(USER.short_description);
        return false;
    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
