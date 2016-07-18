var Login = {
    init: function() {
        this.cacheElements();
        this.bindEvents();
    },
    cacheElements: function() {
    	this.$form = $('form#login-form');
		this.$submit = this.$form.find('button[type="submit"]');
    },
    bindEvents: function() {
    	this.$submit.click(Default.submitClick);
    }    
}

$(document).ready(function () {
    Login.init();
});
