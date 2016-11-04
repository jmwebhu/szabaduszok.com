var ProjectPartner = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function () {
        this.$apply             = $('a#apply');
        this.$undoApplication   = $('a#undoApplication');
    },
    bindEvents: function () {
        this.$apply.click(ProjectPartner.applyClick);
        this.$undoApplication.click(ProjectPartner.undoApplicationClick);
    },
    applyClick: function () {
        var $this = $(this);
        $this.prop('disabled', true);
        var ajax = new AjaxBuilder();

        var data = {
            project_id: $this.data('project_id')
        };

        var beforeSend = function () {
            $this.prop('disabled', true);
            Default.startLoading();
        };

        var success = function(data) {
            Default.stopLoading(data.error, 'Sikeres jelentkezés');
            $this.prop('disabled', false);
        };

        ajax.data(data).url(ROOT + 'projectpartner/ajax/apply').beforeSend(beforeSend).success(success).send();
        
        return false;
    },
    undoApplicationClick: function () {

    }
};

$(document).ready(function () {
    ProjectPartner.init();
});
