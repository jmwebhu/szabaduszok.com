var Default = {
	getSelect2Object: function (url) {
		return {
			theme: "bootstrap",
		    placeholder: "Kezdj el gépelni...",
		    ajax: {
		        url: url,
		        type: 'get',
		        dataType: 'json',
		        delay: 250,
		        data: function (params) {
		            return {
		                term: params.term // search term
		            };
		        },
		        processResults: function (data) {
		            return {
		                results: data
		            };
		        },
		        cache: false
		    },
		    minimumInputLength: 1,
		    tags: true,
		    tokenSeparators: [',',';']
		};	
	},
	submitClick: function () {
		$.isLoading({ text: "Folyamatban..." });
		
		var $button = $(this);
		
		$button.prop('disabled', true);
		
		var isValid = Validator.validateForm($button.parent('form'));			
		if (!isValid) {
			$button.prop('disabled', false);
			
			$.isLoading("hide"); 
		}
		
		$button.prop('disabled', false);
		
		return isValid;
	},
	getFloatFromString: function (string) {
	    if (typeof string === 'number') {
	        return parseFloat(string);
	    }
	    
	    if (typeof string === 'undefined') {
	        return string;
	    }
	    
	    if (string == null) {
	        return string;
	    }
	    
	    var s = string.replace(' ', '');
	    var float = s.replace(',', '.');
	    float = $.trim(float);
	    float = parseFloat(float);
	    
	    if (isNaN(float)) {
	        return 0;
	    }
	    
	    return float;
	},
	startLoading: function (text) {
        if (typeof text == 'undefined') {
            text = 'Folyamtban...';
        }

        $('span.loading').text(text).isLoading();
	},
    stopLoading: function (error, successText) {

        if (typeof successText == 'undefined') {
            successText = 'Sikeres művelet';
        }

        var $loading = $('span.loading');
        $loading.isLoading('hide');

        if (error) {
            $loading.html('Sajnos, valami hiba történt...').addClass('alert-danger');
            $loading.show();
        } else {
            $loading.html(successText).addClass('alert-success');
            $loading.show();
        }

        setTimeout(function () {
            $loading.hide();
        }, 1000);
    }
};

var fancyBoxOptions = {
    maxWidth	: '80%',
    maxHeight	: '80%',
    fitToView	: false,
    width	    : '80%',
    height	    : '80%',
    autoSize	: false,
    openEffect	: 'none',
    closeEffect	: 'none',
    closeClick  : false,
    padding		: 0,
    closeBtn	: true,
    helpers		: { 
		overlay : {
			closeClick: false
		}
	}
 };


$(document).ready(function() {	                
        
	$('button#nav-toggle').click(function () {		
		
		if ($('div.navbar-1').hasClass('in')) {
			$('#hero').removeClass('hero-transition');
		} else {			
			$('#hero').addClass('hero-transition');
		}				
	});
});