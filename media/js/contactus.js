var ContactUs = {
	init: function () {
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();				
	},
	cacheElements: function () {
		this.$form = $('form#contact-form');
		this.$submit = this.$form.find('button[type="submit"]');
	},
	bindEvents: function () {
		this.$submit.click(Default.submitClick);
	},
	addWidgets: function () {				
	}
};

$(document).ready(function () {
	ContactUs.init();
});