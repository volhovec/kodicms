function strtr (str, from, to) {
	if (typeof from === 'object') {
		var cmpStr = '';
		for (var j=0; j < str.length; j++){
			cmpStr += '0';
		}
		var offset = 0;
		var find = -1;
		var addStr = '';
		for (fr in from) {
			offset = 0;
			while ((find = str.indexOf(fr, offset)) != -1){
				if (parseInt(cmpStr.substr(find, fr.length)) != 0){
					offset = find + 1;
					continue;
				}
				for (var k =0 ; k < from[fr].length; k++){
					addStr += '1';
				}
				cmpStr = cmpStr.substr(0, find) + addStr + cmpStr.substr(find + fr.length, cmpStr.length - (find + fr.length));
				str = str.substr(0, find) + from[fr] + str.substr(find + fr.length, str.length - (find + fr.length));
				offset = find + from[fr].length + 1;
				addStr = '';
			}
		}
		return str;
	}

	for(var i = 0; i < from.length; i++) {
		str = str.replace(new RegExp(from.charAt(i),'g'), to.charAt(i));
	}

	return str;
}


// Skip errors when no access to console
var console = console || {log:function () {}};


// Main object
var cms = {
	
	models: {},
	views: {},
	collections: {},
	routes: {},
	event: _.extend({}, Backbone.Events),
	
	// Error
	error: function (msg, e) {
		this.message(msg, 'error')
		$.jGrowl(msg, {theme: 'alert alert-error'});
	},
		
	message: function(msg, type) {
		if(!type) type = 'success';
		window.top.$.jGrowl(decodeURI(msg), {theme: 'alert alert-' + type});
		
		if(type == 'error') {
			cms.error_field(name, msg)
		}
	},
	error_field: function(name, message) {
		return input = $('input[name*="' + name + '"]:not(:hidden)', $('.control-group:not(.error)'))
			.after('<span class="help-inline">' + message + '</span>')
			.parentsUntil( '.control-group' )
			.parent()
			.addClass('error');
	},
	// Convert slug
	convert_dict: {
		'ą':'a', 'ä':'a', 'č':'c', 'ę':'e', 'ė':'e', 'i':'i', 'į':'i', 'š':'s', 'ū':'u', 'ų':'u', 'ü':'u', 'ž':'z', 'ö':'o'
	},
	
	convertSlug: function (str) {
		return str
			.toString()
			.toLowerCase()
			.replace(/[àâ]/g, 'a')
			.replace(/[éèêë]/g, 'e')
			.replace(/[îï]/g, 'i')
			.replace(/[ô]/g, 'o')
			.replace(/[ùû]/g, 'u')
			.replace(/[ñ]/g, 'n')
			.replace(/[äæ]/g, 'ae')
			.replace(/[öø]/g, 'oe')
			.replace(/[ü]/g, 'ue')
			.replace(/[ß]/g, 'ss')
			.replace(/[å]/g, 'aa')
			.replace(/(.)/g, function (c) {
				return (cms.convert_dict[c] != undefined ? cms.convert_dict[c] : c);
			})
			.replace(/[^a-zа-яіїє0-9\.\_]/g, '-')
			.replace(/ /g, '-')
			.replace(/\-+/g, '-')
			.replace(/^-/, '');
	},
	
	// Loader
	loader: {
		init: function () {
			$('body')
				.append('<div class="_loader_container"><div class="_loader_bg"></div><span>' + __('Loading') + '</span>\n\
</div>');
		},
		
		show: function (speed) {
			if(!speed)
				speed = 1500
			$('._loader_container').fadeTo(speed, 0.4);
		},
		
		hide: function () {
			$('._loader_container').stop().fadeOut();
		}
	},
	
	translations: {},
	
	// Plugins
	plugins: {},
	
	// Messages
	messages: {
		init: function () {
			$('.message')
				.animate({top:0}, 1000);
		}
	},
	
	// Filters
	filters: {
		// Filters array
		filters: [],
		switchedOn: {},
		
		// Add new filter
		add: function (name, to_editor_callback, to_textarea_callback) {
			if (to_editor_callback == undefined || to_textarea_callback == undefined) {
				cms.error('System try to add filter without required callbacks.', name, to_editor_callback, to_textarea_callback);
				return;
			}

			this.filters.push([ name, to_editor_callback, to_textarea_callback ]);
		},
		
		// Switch On filter
		switchOn: function (textarea_id, filter, params) {

			jQuery('#' + textarea_id).css('display', 'block');

			if (this.filters.length > 0) {
				// Switch off previouse editor with textarea_id
				cms.filters.switchOff(textarea_id);

				for (var i = 0; i < this.filters.length; i++) {
					if (this.filters[i][0] == filter) {
						try {
							// Call handler that will switch on editor
							this.filters[i][1](textarea_id, params);

							// Add editor to switchedOn stack
							cms.filters.switchedOn[textarea_id] = this.filters[i];
						}
						catch (e) {
							//frog.error('Errors with filter switch on!', e);
						}

						break;
					}
				}
			}
		},
		
		// Switch Off filter
		switchOff: function (textarea_id) {
			for (var key in cms.filters.switchedOn) {
				// if textarea_id param is set we search only one editor and switch off it
				if (textarea_id != undefined && key != textarea_id)
					continue;
				else
					textarea_id = key;

				try {
					if (cms.filters.switchedOn[key] != undefined && cms.filters.switchedOn[key] != null && typeof(cms.filters.switchedOn[key][2]) == 'function') {
						// Call handler that will switch off editor and showed up simple textarea
						cms.filters.switchedOn[key][2](textarea_id);
					}
				}
				catch (e) {
					//cms.error('Errors with filter switch off!', e);
				}

				// Remove editor from switchedOn editors stack
				if (cms.filters.switchedOn[key] != undefined || cms.filters.switchedOn[key] != null) {
					cms.filters.switchedOn[key] = null;
				}
			}
		}
	}
};

cms.addTranslation = function (obj) {
    for (var i in obj) {
        cms.translations[i] = obj[i];
    }
};

var __ = function (str, values) {
    if (cms.translations[str] !== undefined)
	{
		var str = cms.translations[str];
	}

    return values == undefined ? str : strtr(str, values);
};

cms.ui = {
    callbacks:[],
    add:function (module, callback) {
        if (typeof(callback) != 'function')
            return this;

        cms.ui.callbacks.push([module, callback]);
		
		return this;
    },
    init:function () {
        for (var i = 0; i < cms.ui.callbacks.length; i++) {
			try {
				cms.ui.callbacks[i][1]();
			} catch (e) {}
        }
    }
};

// Pages init
cms.init = {
	callbacks:[],
	add:function (rout, callback) {
		if (typeof(callback) != 'function')
			return false;

		if (typeof(rout) == 'object') {
			for (var i = 0; i < rout.length; i++)
				cms.init.callbacks.push([rout[i], callback]);
		}
		else if (typeof(rout) == 'string')
			cms.init.callbacks.push([rout, callback]);
		else
			return false;
	},
	run:function () {
		var body_id = $('body:first').attr('id').toString();

		for (var i = 0; i < cms.init.callbacks.length; i++) {
			var rout_to_id = 'body_' + cms.init.callbacks[i][0];

			if (body_id == rout_to_id)
				cms.init.callbacks[i][1]();
		}
	}
};

cms.ui.add('btn-confirm', function() {
	$('body').live('click', '.btn-confirm', function () {
		if (confirm(__('Are you sure?')))
			return true;

		return false;
	});
}).add('spoiler', function() {
	$('.spoiler-toggle')
		.click(function () {
			var $self = $(this);
			var $spoiler_cont = $('.spoiler');
			
			if($(this).data('spoiler')) {
				$spoiler_cont = $($(this).data('spoiler'));
			}
		
			$spoiler_cont.slideToggle('fast', function() {
				var $icon = $self.find('.spoiler-toggle-icon');
				if($(this).is(':hidden')) {
					$icon.removeClass('icon-chevron-up').addClass('icon-chevron-down');
				} else {
					$icon.addClass('icon-chevron-up').removeClass('icon-chevron-down');
				}
			});
			
			return false;
		}).each(function() {
			if($(this).data('hash') == window.location.hash.substring(1))
			{
				$(this).click();
				$('html,body').animate({scrollTop: $(this).offset().top}, 'slow');
			}
			
		});
}).add('datepicker', function() {
	// Datepicker
    $('.datepicker').datepicker({
        // options
        dateFormat:'yy-mm-dd',

        // events
        onSelect:function (dateText, inst) {
            inst.input.val(dateText + ' 00:00:00');
        }
    });
}).add('slug', function() {
	// Slug & metadata
    var slugs = {};
    $('body').on('keyup', '.slug-generator', function () {
		var $slug_cont = $('.slug');

		if($(this).data('slug')) {
			$slug_cont = $($(this).data('slug'));
		}
		
        if ($slug_cont.val() == '')
            slugs[$slug_cont] = true;

        if (slugs[$slug_cont]) {
            var new_slug = cms.convertSlug($(this).val());

            $slug_cont.val(new_slug);
        }
    });

	$('body').on('keyup', '.slug', function () {
			$(this).val(cms.convertSlug($(this).val()));
			slugs[$(this)] = false;
			
			if ($(this).val() == '')
				slugs[$(this)] = true;
		});
}).add('focus', function() {
	$('.focus').focus();
}).add('loader', function() {
    cms.loader.init();
}).add('fancybox', function() {
    $(".fancybox-image").fancybox();
}).add('popup', function() {
	$(".popup").fancybox({
		fitToView	: true,
		autoSize	: false,
		width		: '99%',
		height		: '99%',
		openEffect	: 'none',
		closeEffect	: 'none',
		beforeLoad: function() {
			this.href += '?type=iframe';
			this.title = $(this.element).html();
		},
		helpers : {
    		title : {
    			type : 'inside'
    		}
    	}
	});

	var method = ACTION == 'add' ? 'put' : 'post';
	var $form_actions = $('.iframe .form-actions');
	
	$('.btn-save', $form_actions).on('click', function() {
		var $data = $('form').serializeObject();
		Api[method](CONTROLLER, $data);
		return false;
	});

	$('.btn-save-close', $form_actions).on('click', function() {
		var $data = $('form').serializeObject();
		Api[method](CONTROLLER, $data, function(response) {
			window.top.$.fancybox.close();
		});
		return false;
	});

	$('.btn-close', $form_actions).on('click', function() {
		window.top.$.fancybox.close();
		return false;
	})
}).add('select2', function() {
	var select = $('select').not('.no-script');
	select.select2();
});

var Api = {
	_response: null,

	get: function(uri, data, callback) {
		this.request('GET', uri, data, callback);
		
		return this.response();
	},
	post: function(uri, data, callback) {
		this.request('POST', uri, data, callback);
		
		return this.response();
	},
	put: function(uri, data, callback) {
		this.request('PUT', uri, data, callback);
		
		return this.response();
	},

	'delete': function(uri, data, callback) {
		this.request('DELETE', uri, data, callback);
		
		return this.response();
	},

	request: function(method, uri, data, callback) {
		uri = SITE_URL + 'api/' + uri;
		
		$.ajaxSetup({
			contentType : 'application/json'
		});

		if(typeof(data) == 'object' && method != 'GET') 
			data = JSON.stringify(data);

		$.ajax({
			type: method,
			url: uri,
			data: data,
			dataType: 'json',
//			cache: false,
			beforeSend: function(){
				cms.loader.show();
			},
			success: function(response) {
				if(response.code != 200) return Api.exception(response);
				
				if (response.message) {
					cms.message(response.message);
				}
	
				if(response.redirect) {
					$.get(window.top.CURRENT_URL, function(resp){
						window.top.$('#content').html(resp);
						
						window.location = response.redirect + '?type=iframe';
					});
				}
				this._response = response;
				
				var $event = method + uri.replace(/\//g, ':');
				window.top.$('body').trigger($event.toLowerCase(), [this._response.response]);
				
				if(typeof(callback) == 'function') callback(this._response);
			}
		}).always(function() { 
			cms.loader.hide();
		});;
	},

	exception: function(response) {
		if(response.code == 120 && typeof(response.errors) == 'object') {
			for(i in response.errors) {
				cms.message(response.errors[i], 'error');
				cms.error_field(i, response.errors[i]);
			}
		} else if (response.message) {
			cms.message(response.message, 'error');
		}
	},
	response: function() {
		return this._response;
	}
}

// Run
jQuery(document).ready(function () {
    // messages
    cms.messages.init();

    // init
    cms.init.run();
    cms.ui.init();
	
	for(error in MESSAGE_ERRORS) {
		cms.message(MESSAGE_ERRORS[error], 'error');
		cms.error_field(error, MESSAGE_ERRORS[error]);
	}
	
	for(text in MESSAGE_SUCCESS) {
		cms.message(MESSAGE_SUCCESS[text]);
	}
});

// Checkbox status
$.fn.check = function () {
    return this.each(function () {
        this.checked = true;
    });
};

$.fn.uncheck = function () {
    return this.each(function () {
        this.checked = false;
    });
};

$.fn.checked = function () {
    return this.attr('checked');
};

$.fn.tabs = function () {
    return $('li a', this).on('click', function() {
		$(this)
			.parent()
			.addClass('active')
			.siblings()
			.removeClass('active');

		$('div.tab-pane').removeClass('active');
		$($(this).attr('href')).addClass('active');
		
		return false;
	});
};

$.fn.serializeObject = function()
{
    var o = {};
    var a = this.serializeArray();
    $.each(a, function() {
        if (o[this.name] !== undefined) {
            if (!o[this.name].push) {
                o[this.name] = [o[this.name]];
            }
            o[this.name].push(this.value || '');
        } else {
            o[this.name] = this.value || '';
        }
    });
    return o;
};