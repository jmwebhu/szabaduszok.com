var ProjectownerReg = {
	init: function () {
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();	
		
		/**
		 * private.szabaduszok.com launch elott az assetmerger Parse error -t dobott, ha 
		 * egy if volt, es benne && operator
		 */		
		if (typeof ProjectownerReg.$userId !== 'undefined') {
			
			if (typeof ProjectownerReg.$userId.val() != 'undefined') {
				
				if (this.$isCompany.prop('checked') == true) {
					this.isCompanyChange();
				}
			}											
		}			
	},
	cacheElements: function () {
		this.$form = $('form#projectowner-form');
		this.$submit = $('button[type="submit"]');
		
		this.$industries = $('select#industries');
		this.$professions = $('select#professions');
		
		this.$isCompany = $('input#is-company');
		
		this.$companyNameContainer = $('input#company-name').parent('div.form-group:first');
		this.$companyNameSpan = this.$companyNameContainer.prev('span.icon-sm:first');		
		
		this.$contactNameContainer = $('input#contact-name').parent('div.form-group:first');		
		this.$contactNameSpan = this.$contactNameContainer.prev('span.icon-sm:first');
		
		this.$userId = $('input[name="user_id"]');
	},
	bindEvents: function () {
		this.$submit.click(Default.submitClick);
		
		this.$isCompany.change(ProjectownerReg.isCompanyChange);
	},
	addWidgets: function () {
		
		this.$industries.select2({
			theme: "bootstrap"
		});		
		
		var professionUrl = ROOT + 'project/ajax/professionAutocomplete';
		var professionObj = Default.getSelect2Object(professionUrl);
		
        this.$professions.select2(professionObj);                
	},
	isCompanyChange: function () {			
		ProjectownerReg.$companyNameContainer.toggleClass('hidden');
		ProjectownerReg.$companyNameSpan.toggleClass('hidden');
		
		ProjectownerReg.$contactNameContainer.toggleClass('hidden');
		ProjectownerReg.$contactNameSpan.toggleClass('hidden');
	}
};

$(document).ready(function () {
	ProjectownerReg.init();
});