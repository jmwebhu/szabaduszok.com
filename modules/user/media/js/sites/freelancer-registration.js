var FreelancerReg = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
        this.addWidgets();				
    },
    cacheElements: function () {
        this.$form = $('form');
        this.$submit = this.$form.find('button[type="submit"]');
        
        this.$industries = this.$form.find('select#industries');
        this.$professions = this.$form.find('select#professions');
        this.$skills = this.$form.find('select#skills');

        this.$next = this.$form.find('button.next');
        this.$prev = this.$form.find('button.prev');
    },
    bindEvents: function () {
        this.$submit.click(Default.submitClick);
        this.$next.click(FreelancerReg.nextClick);
        this.$prev.click(FreelancerReg.prevClick);
    },
    addWidgets: function () {
        this.$industries.select2({
                theme: "bootstrap"
        });		
        
        var professionUrl = ROOT + 'project/ajax/professionAutocomplete';
        var professionObj = Default.getSelect2Object(professionUrl);
        
        var skillUrl = ROOT + 'project/ajax/skillAutocomplete';
        var skillObj = Default.getSelect2Object(skillUrl);
            
        this.$professions.select2(professionObj);                
        this.$skills.select2(skillObj);
    },
    nextClick: function () {
        var $nextStep = $('ul.steps li.active').next('li');
        if ($nextStep.length !== 0) {
            $nextStep.trigger('click');
        }
    },
    prevClick: function () {
        var $prevStep = $('ul.steps li.active').prev('li');
        if ($prevStep.length !== 0) {
            $prevStep.trigger('click');
        }
    }
};

$(document).ready(function () {
    FreelancerReg.init();
});
