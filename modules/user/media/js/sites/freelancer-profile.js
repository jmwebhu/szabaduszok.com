var FreelancerProfile = {
	init: function () {
		this.cacheElements();
		this.bindEvents();
		this.addWidgets();				
	},
	cacheElements: function () {
		this.$rating = $('select#rating');
		
		this.$form = $('form#project-notification-form');
		
		this.$industries = this.$form.find('select#industries');
		this.$professions = this.$form.find('select#professions');
		this.$skills = this.$form.find('select#skills');
		this.$skillRelation = this.$form.find('select#skill-relation');
		this.$saveProjectNotification = this.$form.find('button#save-project-notification');
		this.$loading = $('span.loading');
		
		this.$initialRating = $('input#initial-rating');
		
		this.$rateButton = $('button#rate');
		this.$yourRatingPoint = $('span#your-rating-point');
		this.$ratingInstruction = $('p.rating-instruction');
		this.$ratingAvgPoint = $('span#rating-avg-point');
		this.$ratersCount = $('span#raters-count');
		
		this.$canRate = $('input#can-rate');
	},
	bindEvents: function () {			
		this.$rateButton.click(FreelancerProfile.rateClick);
		this.$saveProjectNotification.click(FreelancerProfile.saveProjectNotificationClick);
	},
	addWidgets: function () {
		FreelancerProfile.$rating.barrating({
	        theme: 'fontawesome-stars',
	        initialRating: FreelancerProfile.$initialRating.val(),
	        readonly: (FreelancerProfile.$canRate.val() == '0')
	    });

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
	},
	/**
	 * Projekt ertesito mentes
	 */
	saveProjectNotificationClick: function () {
		
		var $this = $(this);
		$this.prop('disabled', true);		
		
		FreelancerProfile.$loading.isLoading({
            text:       "Folyamatban...",
            //position:   'overlay'
        });
		
		FreelancerProfile.$loading.removeClass("alert-success");
		
		var ajax = new AjaxBuilder();
		var success = function(data) {						

			setTimeout(function() {
				FreelancerProfile.$loading.isLoading('hide');
			}, 500);

			if (data.error) {
				setTimeout(function () {
					FreelancerProfile.$loading.html('Sajnos, valami hiba történt...').addClass('alert-danger');
					FreelancerProfile.$loading.show();
				}, 500);				
				
				setTimeout(function () {
					FreelancerProfile.$loading.hide(); 
				}, 2000);			
			} else {
				setTimeout(function () {
					FreelancerProfile.$loading.html('Sikeres mentés').addClass('alert-success');
					FreelancerProfile.$loading.show();
				}, 500);					
				 
				setTimeout(function () {
					FreelancerProfile.$loading.hide(); 
				}, 1000);				
			}	
			
			setTimeout(function() {
				$this.prop('disabled', false);
			}, 500);
		};
		
		ajax.data(FreelancerProfile.$form.serialize()).url(FreelancerProfile.$form.attr('action')).success(success).send();
		
		return false;
	},
	/**
	 * Ertekeles
	 */
	rateClick: function () {						
		
		var $this = $(this);
		$this.prop('disabled', true);
		
		var ratingValue = FreelancerProfile.$rating.val();		
		
		$.confirm({
			icon: 'fa fa-question-circle',
		    title: 'Megerősítés',
		    content: 'Biztosan <strong>' + ratingValue + '</strong> ponttal értékeled a felhasználót?',
		    confirm: function () {
		        FreelancerProfile.sendRateAjax($this, ratingValue);
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
				
				FreelancerProfile.$yourRatingPoint.text(data.rating);
				FreelancerProfile.$ratingAvgPoint.text(data.avg);
				FreelancerProfile.$ratersCount.text(data.raters_count);
				
				FreelancerProfile.$ratingInstruction.fadeOut(function () {
					FreelancerProfile.$ratingInstruction.hide();
				});
				
				FreelancerProfile.$rateButton.fadeOut(function () {
					FreelancerProfile.$rateButton.hide();
				});								
				
				FreelancerProfile.$rating.barrating('set', parseInt(data.avg));
				FreelancerProfile.$rating.barrating('readonly', true);
			}
		};
		
		ajax.data({rating: ratingValue, user_id: userId}).url(ROOT + 'user/ajax/rate').success(success).send();
	}
};

$(document).ready(function () {
	FreelancerProfile.init();
});