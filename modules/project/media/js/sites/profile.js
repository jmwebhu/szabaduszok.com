var ProjectProfile = {
    init: function () {
        this.cacheElements();
        this.bindEvents();
        this.addWigdets();
    },
    cacheElements: function () {
        this.$del = $('button#del');
        this.$submit = $('button[type="submit"]');

        this.$userSlug = $('input[name="user_slug"]');
        
        this.$loading = $('span.loading');
        
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
        ProjectProfile.$del.click(ProjectProfile.delClick);  
        this.$rateButton.click(ProjectProfile.rateClick);
    },
    addWigdets: function () {
    	ProjectProfile.$rating.barrating({
	        theme: 'fontawesome-stars',
	        initialRating: ProjectProfile.$initialRating.val(),
	        readonly: (ProjectProfile.$canRate.val() == '0')
	    });
    },
    /**
	 * Torles
	 */
	delClick: function () {										
		
		var $this = $(this);
		$this.prop('disabled', true);				
		
		$.confirm({
			icon: 'fa fa-question-circle',
		    title: 'Törlés',
		    content: 'Biztosan törlöd a projektet?',
		    confirm: function () {
		        ProjectProfile.sendInactivateAjax($this.data('id'));
		    },
		    cancel: function () {
		        $this.prop('disabled', false);
		    },
		    confirmButton: 'IGEN',
		    cancelButton: 'NEM'
		});				
	},
	sendInactivateAjax: function (id) {
		
		ProjectProfile.$loading.isLoading({
            text:       "Folyamatban..."
        });
		
		var ajax = new AjaxBuilder();
		var success = function(data) {						
			
			setTimeout(function() {
				ProjectProfile.$loading.isLoading('hide');
			}, 500);	
			
			if (data.error) {
				setTimeout(function () {
					ProjectProfile.$loading.html('Sajnos, valami hiba történt...').addClass('alert-danger');
					ProjectProfile.$loading.show();
				}, 500);				
				
				setTimeout(function () {
					ProjectProfile.$loading.hide(); 
				}, 2000);
				
				setTimeout(function () {
					ProjectProfile.$del.prop('disabled', false);
				}, 2000);				
			} else {
				setTimeout(function () {
					ProjectProfile.$loading.html('Sikeres törlés').addClass('alert-success');
					ProjectProfile.$loading.show();
				}, 500);					
				 
				setTimeout(function () {
					ProjectProfile.$loading.hide(); 
				}, 1000);				
			}		
			
			window.location.replace(ROOT + 'megbizo/' + ProjectProfile.$userSlug.val());
		};

		var error = function () {
			setTimeout(function () {
				ProjectProfile.$loading.html('Sajnos, valami hiba történt...').addClass('alert-danger');
				ProjectProfile.$loading.show();
			}, 500);
		};
		
		ajax.data({id: id}).url(ROOT + 'project/ajax/inactivate').success(success).error(error).send();
	},
	/**
	 * Ertekeles
	 */
	rateClick: function () {						
		
		var $this = $(this);
		$this.prop('disabled', true);
		
		var ratingValue = ProjectProfile.$rating.val();		
		
		$.confirm({
			icon: 'fa fa-question-circle',
		    title: 'Megerősítés',
		    content: 'Biztosan <strong>' + ratingValue + '</strong> ponttal értékeled a felhasználót?',
		    confirm: function () {
		    	ProjectProfile.sendRateAjax($this, ratingValue);
		    },
		    cancel: function () {
		        $this.prop('disabled', false);
		    },
		    confirmButton: 'IGEN',
		    cancelButton: 'NEM'
		});				
	},
	sendRateAjax: function ($button, ratingValue) {
		var userId = $button.data('user_id');
		
		var ajax = new AjaxBuilder();
		var success = function (data) {
			if (data.error) {
				$button.prop('disabled', false);
			} else {
				
				ProjectProfile.$yourRatingPoint.text(data.rating);
				ProjectProfile.$ratingAvgPoint.text(data.avg);
				ProjectProfile.$ratersCount.text(data.raters_count);
				
				ProjectProfile.$ratingInstruction.fadeOut(function () {
					ProjectProfile.$ratingInstruction.hide();
				});
				
				ProjectProfile.$rateButton.fadeOut(function () {
					ProjectProfile.$rateButton.hide();
				});								
				
				ProjectProfile.$rating.barrating('set', parseInt(data.avg));
				ProjectProfile.$rating.barrating('readonly', true);
			}
		};
		
		ajax.data({rating: ratingValue, user_id: userId}).url(ROOT + 'user/ajax/rate').success(success).send();
	}
};

$(document).ready(function () {
	ProjectProfile.init();
});
