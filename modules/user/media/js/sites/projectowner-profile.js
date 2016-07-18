var ProjectownerProfile = {
		
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
		this.$rateButton.click(ProjectownerProfile.rateClick);
	},
	addWidgets: function () {		
		ProjectownerProfile.$rating.barrating({
	        theme: 			'fontawesome-stars',
	        initialRating:	(ProjectownerProfile.$initialRating.val().length == 0) ? 0 : ProjectownerProfile.$initialRating.val(),
	        readonly: 		(ProjectownerProfile.$canRate.val() == '0') ? true : false, 
	    });		
	},	
	/**
	 * Ertekeles
	 */
	rateClick: function () {						
		
		var $this = $(this);
		$this.prop('disabled', true);
		
		var ratingValue = ProjectownerProfile.$rating.val();		
		
		$.confirm({
			icon: 'fa fa-question-circle',
		    title: 'Megerősítés',
		    content: 'Biztosan <strong>' + ratingValue + '</strong> ponttal értékeled a felhasználót?',
		    confirm: function () {
		        ProjectownerProfile.sendRateAjax($this, ratingValue);
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
				
				ProjectownerProfile.$yourRatingPoint.text(data.rating);
				ProjectownerProfile.$ratingAvgPoint.text(data.avg);
				ProjectownerProfile.$ratersCount.text(data.raters_count);
				
				ProjectownerProfile.$ratingInstruction.fadeOut(function () {
					ProjectownerProfile.$ratingInstruction.hide();
				});
				
				ProjectownerProfile.$rateButton.fadeOut(function () {
					ProjectownerProfile.$rateButton.hide();
				});								
				
				ProjectownerProfile.$rating.barrating('set', parseInt(data.avg));
				ProjectownerProfile.$rating.barrating('readonly', true);
			}
		};
		
		ajax.data({rating: ratingValue, user_id: userId}).url(ROOT + 'user/ajax/rate').success(success).send();
	}
};

$(document).ready(function () {
	ProjectownerProfile.init();
});