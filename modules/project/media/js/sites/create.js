var ProjectCreate = {
    init: function() {
        this.cacheElements();
        this.bindEvents();
        this.addWidgets();               
    },
    cacheElements: function() {
    	
    	this.$form = $('form#project-form');
    	this.$submit = this.$form.find('button[type="submit"]');        
    	
    	this.$industries = this.$form.find('select#industries');
		this.$professions = this.$form.find('select#professions');
		this.$skills = this.$form.find('select#skills');
		
		this.$longDescription = this.$form.find('textarea#long-description');
    },
    bindEvents: function() {
    	this.$submit.click(Default.submitClick);
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
        
        this.$longDescription.wysihtml5({        	
        	toolbar: {
        		"fa": true,
        		"blockquote": false,
        		"link": false, 
        	    "image": false
        	}
        });
    }
};

$(document).ready(function() {
    ProjectCreate.init();
});
