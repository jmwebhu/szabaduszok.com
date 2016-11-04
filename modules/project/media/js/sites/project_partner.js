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
            console.log('before');
            $this.attr('disabled', true);
            $('span.loading').text('Folyamatban...').isLoading();
        };

        var success = function(data) {
            console.log('success');
            var $loading = $('span.loading');
            $loading.isLoading('hide');

            if (data.error) {
                $loading.html('Sajnos, valami hiba történt...').addClass('alert-danger');
                $loading.show();
            } else {
                $loading.html('Sikeres jelentkezés').addClass('alert-success');
                $loading.show();
            }

            setTimeout(function () {
                $loading.hide();
            }, 2000);

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
