var Default = {
    initSocket: function () {
        Default.socket = io.connect(SOCKETURL, {
            query: 'room=' + USERID,
            autoConnect: true
        });

        Default.socket.on("new_message", function(data) {
            
            var $div = $('div.conversation[data-id="' + data.conversation_id + '"]');            

            // Egyaltalan nincs ilyen div az oldalon (mert nem az uzenetek oldalon van a user)
            // Vagy az uzenetek oldalon van, de nem ez a kijeleolt beszelgetes
            // Ha ez a kijelolt, akkor nem kell az olvasatlan uzenetek szamat frissiteni
            if ($div.length == 0 || !$div.hasClass('selected')) {
                Default.updateUreadNumber(data.unread_count);    
            }
        });

        Default.socket.on("update-count", function(data) {
           Default.updateUreadNumber(data); 
        });
    },
    updateUreadNumber: function (count) {
        var $span = $('#unread-message-number');

        if (count != 0) {
            $span.find('i').text(count);    
            $span.removeClass('hidden');
        } else {
            $span.addClass('hidden');
        }
    },
	getSelect2Object: function (url) {
		return {
			theme: "bootstrap",
		    placeholder: 'Kezdj el gépelni...',
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
	startLoading: function ($loading, text) {

        $loading = Default.getLoading($loading);

        if (typeof text == 'undefined') {
            text = 'Folyamtban...';
        }

        $loading.text(text).isLoading();
	},
    stopLoading: function (error, successText, $loading) {
        $loading = Default.getLoading($loading);

        if (typeof successText == 'undefined') {
            successText = 'Sikeres művelet';
        }

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
    },
    getLoading: function($loading) {
        if (typeof $loading == 'undefined') {
            $loading = $('span.loading');
        }

        return $loading;
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
    
    Default.initSocket();    

	$('button#nav-toggle').click(function () {		
		
		if ($('div.navbar-1').hasClass('in')) {
			$('#hero').removeClass('hero-transition');
		} else {			
			$('#hero').addClass('hero-transition');
		}				
	});
});
