var ProjectList = {
	init : function() {
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();	
		
		this.$ul.find('li[data-content="' + this.$ul.data('current') + '"]').trigger('click');
	},
	cacheElements : function() {
		this.$complexForm = $('form#complex-search-form');
		
		this.$industries = this.$complexForm.find('select#industries');
		this.$professions = this.$complexForm.find('select#professions');
		this.$skills = this.$complexForm.find('select#skills');
		this.$skillRelation = this.$complexForm.find('select#skill-relation');
		
		this.$ul = $('ul.nav-tabs');
	},
	bindEvents : function() {

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
	}
};

$(document).ready(function() {
	ProjectList.init();
});
