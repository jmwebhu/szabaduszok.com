var Validator = {
				
	map: function (label) {
            var labels = {
                'notEmpty'			: 'Ezt a mezőt kérlek ne hagyd üresen',
                'notEmptySelect'	: 'Kérlek válassz egyet',
                'email'				: 'Kérlek érvényes e-mail címet adj meg.',
                'longer'			: 'Próbálj meg hosszab értéket megadni',
                'number'			: 'Kérlek itt csak számokat használj',
                'letters'			: 'Kérlek itt csak betűket használj',
                'sameAs'			: 'Kérlek a két mezőbe ugyanazt az értéket írd',
                'url'				: 'Kérlek adj meg egy helyes URL -t',
                'profile'			: 'Kérlek add meg a profilod URL -jét'
            };

            return labels[label];
	},
		
	notEmpty: function (value, $elem) {
		
            var realValue = value;

            if ($elem && $elem.hasClass('tinymce')) {
                //realValue = tinyMCE.activeEditor.getContent({format : 'raw'});
            }		

            return !realValue.length == 0;
	},
	notEmptySelect: function (value) {		
            return (value != -1 && value != null && value != '');
	},
	email: function (value) {		
            var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
		
            return regex.test(value);
	},
	longer: function (value, minChar) {
            if (typeof minChar == 'undefined') {
                minChar = 6;
            }

            if (value.length === 0) {
                return true;
            }

            return value.length >= minChar;
	},
	number: function (value) {
		
            if (value.length === 0) {
                return true;
            }

            var regex = /^-?\d+\.?\d*$/;

            return regex.test(value);
	},
	letters: function (value) {
		
            if (value.length === 0) {
                return true;
            }

            var regex = /(?:^|\\s)/;

            return regex.test(value);
	},
	sameAs: function (one, two) {		
            return one == two;
	},
	url: function (url) {
		if (url.length === 0) {
			return true;
		}

		var regex = /(www\.)?[-a-zA-Z0-9@:%._\+~#=]{2,256}\.[a-z]{2,4}\b([-a-zűáéúőóüöíA-ZŰÁÉÚŐÓÜÖÍ0-9@:%_\+.~#?&//=]*)/;

		return regex.test(url);
	},
	profile: function (value, $input, baseUrl) {

		if (value.length === 0) {
			return true;
		}

		return (value.indexOf(baseUrl) == -1) ? false : true;
	},
	
	validateForm: function ($form) {
		
            try {
                var $inputs = $form.find('input');
                var $selects = $form.find('select');
                var $textareas = $form.find('textarea');
                var result;
                var $elem = null;

                $.each ($inputs, function (index, value) {			

                    var $this = $(this);																
                    result = Validator.validateElem($this);

                    if (!result.isValid) {

                        $elem = $this;
                        return false;
                    }
                });			

                if (result.isValid) {
                    $.each ($selects, function (index, value) {

                        var $this = $(this);
                        result = Validator.validateElem($this);

                        if (!result.isValid) {
                            $elem = $this;
                            return false;
                        }
                    });
                }

                if (result.isValid) {
                    $.each ($textareas, function (index, value) {

                        var $this = $(this);
                        result = Validator.validateElem($this);

                        if (!result.isValid) {
                            $elem = $this;
                            return false;
                        }
                    });
                }		

                if (!result.isValid) {
                    throw result;
                }

            } catch (error) {			

                if (typeof $elem !== 'undefined' && $elem !== null) {				

                    $form.find('.form-group').removeClass('has-error');
                    $form.find('span.error-label:not(.general-error-label)').remove();

                    $elem.focus();
                    var $formGroup = $elem.parent('.form-group:first')

                    $formGroup.addClass('has-error');													
                    $formGroup.append("<span class='error-label has-error'>" + Validator.map(error.rule) + "</span>");
                } else {
                    console.log(error);
                    /**
                     * AJAX LOG
                     */
                }				

                return error.isValid;
            }	

            return result.isValid;
	},
	
	validateElem: function ($elem) {
		
		var result = {
                    'isValid'	: true,
                    'rule'      : ''
		};
		
		var isValid = true;
		var rules = $elem.data('rules');				
		
		if (typeof rules !== 'undefined') {
                    $.each (rules, function (rule, value) {																	

                        if (typeof value == 'object') {

                            $.each (value, function (kName, kValue) {

                                isValid = Validator[rule]($elem.val(), kValue);												

                                if (!isValid) {
                                    result.isValid = false;
                                    result.rule = rule;

                                    return false;
                                }
                            });

                            if (!isValid) {
                                return false;
                            }

                        } else {
                            
                            switch (rule) {
                                case 'sameAs':
                                    isValid = Validator[rule]($(value).val(), $elem.val());						
                                    break;                                    
                                    
                                default:
                                    isValid = Validator[rule]($elem.val(), $elem, value);
                                    break;
                            }												
                        }

                        if (!isValid) {

                            result.isValid = false;
                            result.rule = rule;					

                            return false;
                        }
                    });
		}			
		
		return result;
	},
};