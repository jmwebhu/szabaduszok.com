var EmployerReg = {
	init: function () {
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();		
	},
	cacheElements: function () {
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
		this.$isCompany.change(EmployerReg.isCompanyChange);
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
		EmployerReg.$companyNameContainer.toggleClass('hidden');
		EmployerReg.$companyNameSpan.toggleClass('hidden');
		
		EmployerReg.$contactNameContainer.toggleClass('hidden');
		EmployerReg.$contactNameSpan.toggleClass('hidden');
	}
};

$(document).ready(function () {
	EmployerReg.init();
});