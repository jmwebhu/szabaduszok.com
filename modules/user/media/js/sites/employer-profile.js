var EmployerProfile = {
		
	init: function () {		
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();		
	},
	cacheElements: function () {
		this.$rating = $('select#rating');						
		this.$initialRating = $('input#initial-rating');
		
		this.$rateButton = $('button#rate');
		this.$yourRatingPoint = $('span#your-rating-point');
		this.$ratingInstruction = $('p.rating-instruction');
		this.$ratingAvgPoint = $('span#rating-avg-point');
		this.$ratersCount = $('span#raters-count');
		
		this.$canRate = $('input#can-rate');
	},
	bindEvents: function () {			
		this.$rateButton.click(EmployerProfile.rateClick);
	},
	addWidgets: function () {		
		EmployerProfile.$rating.barrating({
	        theme: 			'fontawesome-stars',
	        initialRating:	(EmployerProfile.$initialRating.val().length == 0) ? 0 : EmployerProfile.$initialRating.val(),
	        readonly: 		(EmployerProfile.$canRate.val() == '0') ? true : false, 
	    });		
	},	
	/**
	 * Ertekeles
	 */
	rateClick: function () {						
		
		var $this = $(this);
		$this.prop('disabled', true);
		
		var ratingValue = EmployerProfile.$rating.val();		
		
		$.confirm({
			icon: 'fa fa-question-circle',
		    title: 'Megerősítés',
		    content: 'Biztosan <strong>' + ratingValue + '</strong> ponttal értékeled a felhasználót?',
		    confirm: function () {
		        EmployerProfile.sendRateAjax($this, ratingValue);
		    },
		    cancel: function () {
		        $this.prop('disabled', false);
		    },
		    confirmButton: 'IGEN',
		    cancelButton: 'NEM'
		});				
	},
	sendRateAjax: function ($button, ratingValue, userId) {
		var userId = $button.data('user_id');
		
		var ajax = new AjaxBuilder();
		var success = function (data) {
			if (data.error) {
				$button.prop('disabled', false);
			} else {
				
				EmployerProfile.$yourRatingPoint.text(data.rating);
				EmployerProfile.$ratingAvgPoint.text(data.avg);
				EmployerProfile.$ratersCount.text(data.raters_count);
				
				EmployerProfile.$ratingInstruction.fadeOut(function () {
					EmployerProfile.$ratingInstruction.hide();
				});
				
				EmployerProfile.$rateButton.fadeOut(function () {
					EmployerProfile.$rateButton.hide();
				});								
				
				EmployerProfile.$rating.barrating('set', parseInt(data.avg));
				EmployerProfile.$rating.barrating('readonly', true);
			}
		};
		
		ajax.data({rating: ratingValue, user_id: userId}).url(ROOT + 'user/ajax/rate').success(success).send();
	}
};

$(document).ready(function () {
	EmployerProfile.init();
});