var Freelancers = {
	init: function () {
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();				
		
		this.$ul.find('li[data-content="' + this.$ul.data('current') + '"]').trigger('click');
	},
	cacheElements: function () {
		this.$form = $('form#complex-search-form');
		this.$submit = this.$form.find('button[type="submit"]');
		
		this.$industries = this.$form.find('select#industries');
		this.$professions = this.$form.find('select#professions');
		this.$skills = this.$form.find('select#skills');
		this.$skillRelation = this.$form.find('select#skill-relation');
		
		this.$rating = $('select.rating');
		
		this.$ul = $('ul.nav-tabs');
	},
	bindEvents: function () {
		this.$submit.click(Default.submitClick);
	},
	addWidgets: function () {
		
		this.$industries.select2({
			theme: "bootstrap"
		});		
		
		this.$skillRelation.select2({
			theme: "bootstrap"
		});	
		
		var professionUrl = ROOT + 'project/ajax/professionAutocomplete';
		var professionObj = Default.getSelect2Object(professionUrl);
		
		var skillUrl = ROOT + 'project/ajax/skillAutocomplete';
		var skillObj = Default.getSelect2Object(skillUrl);
		
        this.$professions.select2(professionObj);                
        this.$skills.select2(skillObj);
        
        $.each (Freelancers.$rating, function () {
        	var $this = $(this);
        	
        	$this.barrating({
    	        theme: 'fontawesome-stars',
    	        initialRating: $this.data('init'),
    	        readonly: true
    	    });
        });                
	}
};

$(document).ready(function () {
	Freelancers.init();
});