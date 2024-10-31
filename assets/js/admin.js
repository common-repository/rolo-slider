/**
 * Alpha Color Picker JS
 */
!function(a){function b(a){var b;return a=a.replace(/ /g,""),a.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)?(b=100*parseFloat(a.match(/rgba\(\d+\,\d+\,\d+\,([^\)]+)\)/)[1]).toFixed(2),b=parseInt(b)):b=100,b}function c(a,b,c,e){var f,g,h;f=b.data("a8cIris"),g=b.data("wpWpColorPicker"),f._color._alpha=a,h=f._color.toString(),b.val(h),g.toggler.css({"background-color":h}),e&&d(a,c),b.wpColorPicker("color",h)}function d(a,b){b.slider("value",a),b.find(".ui-slider-handle").text(a.toString())}Color.prototype.toString=function(a){if("no-alpha"==a)return this.toCSS("rgba","1").replace(/\s+/g,"");if(1>this._alpha)return this.toCSS("rgba",this._alpha).replace(/\s+/g,"");var b=parseInt(this._color,10).toString(16);if(this.error)return"";if(b.length<6)for(var c=6-b.length-1;c>=0;c--)b="0"+b;return"#"+b},a.fn.alphaColorPicker=function(){return this.each(function(){var e,f,g,h,i,j,k,l,m,n,o;e=a(this),e.wrap('<div class="alpha-color-picker-wrap"></div>'),g=e.attr("data-palette")||"true",h=e.attr("data-show-opacity")||"true",i=e.attr("data-default-color")||"",j=g.indexOf("|")!==-1?g.split("|"):"false"!=g,f=e.val().replace(/\s+/g,""),""==f&&(f=i),k={change:function(a,c){var d,f,g,h;d=e.attr("data-customize-setting-link"),f=e.wpColorPicker("color"),i==f&&(g=b(f),m.find(".ui-slider-handle").text(g)),"undefined"!=typeof wp.customize&&wp.customize(d,function(a){a.set(f)}),h=l.find(".transparency"),h.css("background-color",c.color.toString("no-alpha"))},palettes:j},e.wpColorPicker(k),l=e.parents(".wp-picker-container:first"),a('<div class="alpha-color-picker-container"><div class="min-click-zone click-zone"></div><div class="max-click-zone click-zone"></div><div class="alpha-slider"></div><div class="transparency"></div></div>').appendTo(l.find(".wp-picker-holder")),m=l.find(".alpha-slider"),n=b(f),o={create:function(b,c){var d=a(this).slider("value");a(this).find(".ui-slider-handle").text(d),a(this).siblings(".transparency ").css("background-color",f)},value:n,range:"max",step:1,min:0,max:100,animate:300},m.slider(o),"true"==h&&m.find(".ui-slider-handle").addClass("show-opacity"),l.find(".min-click-zone").on("click",function(){c(0,e,m,!0)}),l.find(".max-click-zone").on("click",function(){c(100,e,m,!0)}),l.find(".iris-palette").on("click",function(){var c,f;c=a(this).css("background-color"),f=b(c),d(f,m),100!=f&&(c=c.replace(/[^,]+(?=\))/,(f/100).toFixed(2))),e.wpColorPicker("color",c)}),l.find(".button.wp-picker-default").on("click",function(){var a=b(i);d(a,m)}),e.on("input",function(){var c=a(this).val(),e=b(c);d(e,m)}),m.slider().on("slide",function(b,d){var f=parseFloat(d.value)/100;c(f,e,m,!1),a(this).find(".ui-slider-handle").text(d.value)})})}}(jQuery);


jQuery(document).ready( function($) {
	var $v = $('#cmb2-metabox-_rl_highlights_box').find('.postbox').length;

	layout();
	openAnimationBox(initialRun = true);

	$('body').on('keyup change', 'input[id*="_rl_title"]', openAnimationBox);
	$('body').on('keyup change', 'input[id*="_rl_subtitle"]', openAnimationBox);
	$('body').on('keyup change', 'textarea[id*="_rl_desc"]', openAnimationBox);
	$('body').on('keyup change', 'input[id*="rl_button"]', openAnimationBox);
	$('body').on('click', 'button[data-selector="_rl_slide_repeat"]', function(){
		var $o = setInterval(function(){
			var $x = $('#cmb2-metabox-_rl_highlights_box').find('.postbox').length;

			if( $v !== $x ) {
				$v = $('#cmb2-metabox-_rl_highlights_box').find('.postbox').length;
				clearInterval($o);

				if( $('#cmb2-metabox-_rl_highlights_box').find('.postbox').last().find('.color').length <= 0 ) {
					var $slNum = $('#cmb2-metabox-_rl_highlights_box').find('.postbox').last().data('iterator');
					var $fields = ['_rl_slide_'+$slNum+'__rl_title_bg', '_rl_slide_'+$slNum+'__rl_title_clr', '_rl_slide_'+$slNum+'__rl_subtitle_bg', '_rl_slide_'+$slNum+'__rl_subtitle_clr', '_rl_slide_'+$slNum+'__rl_desc_bg', '_rl_slide_'+$slNum+'__rl_desc_clr', '_rl_slide_'+$slNum+'__rl_but_1_txt_clr', '_rl_slide_'+$slNum+'__rl_but_1_bg', '_rl_slide_'+$slNum+'__rl_but_2_txt_clr', '_rl_slide_'+$slNum+'__rl_but_2_bg'];
					var $group = $('#cmb2-metabox-_rl_highlights_box').find('.postbox').last();
					$.each($fields, function(key, val) {
						var $field = val;
						var $name = $field.replace('_'+$slNum+'_', '['+$slNum+'][');
						$name += ']';
						var $html = '<input class="color wp-color-picker" name="'+$name+'" id="'+$name+'" value="" type="text">';

						$group.find('label[for="'+$field+'"]').closest('.cmb-row').find('.cmb-td').html('');
						$group.find('label[for="'+$field+'"]').closest('.cmb-row').find('.cmb-td').html($html);
					});
					$group.find('.color').alphaColorPicker();
				}

				openAnimationBox(initialRun = true, $('#cmb2-metabox-_rl_highlights_box').find('.postbox').last());
			}
		},10);
	});

   $('.toggle').on('click', function(){
	   var $this = $(this);
	   var $id = $this.attr('id');

	   if( $this.closest('._rl_row').hasClass('layout-select') ) {

		   $('#normal-sortables').find('.vis').removeClass('vis');

		   $('#_'+$id).addClass('vis');
	   }

		if( $this.is(':checked') && ! $this.closest('label').hasClass('active') ){
			$this.closest('label').addClass('active').siblings().removeClass('active');
		}

	   if( typeof $this.data('show') != 'undefined' || typeof $this.data('hide') != 'undefined' ) {
		   show_hide($this);
	   }
	});

	$('.toggle').each(function(){
		var $this = $(this);

		if( $this.is(':checked') ) {
			$this.closest('label').addClass('active');
		}

		if( typeof $this.data('show') != 'undefined' || typeof $this.data('hide') != 'undefined' ) {
			show_hide($this);
		}
	})

	$('input[type="range"]').each(function(){
		$(this).closest('div').find('.rng-val').text($(this).val());
	});

	$('input[type="range"]').on('mousemove', function(){
		$(this).closest('div').find('.rng-val').text($(this).val());
	});

	$('.rolo-question').on('mouseleave', function(){
		$(this).fu_popover('hide');
	});

	var $n = 0;
	$('.rolo-question').each(function(){
		$n++;
		var $this = $(this);
		var $id = 'desc-'+$n;
		$this.attr('id', $id);
		var $content = $this.parent().find('.question-bulb').html();
		var $title = $this.parent().find('label').html();
		var $options = {
			title: $title,
			content: $content,
			trigger : 'hover',
			placement: 'left',
			dismissable: true,
			width: '360px'
		};

		$('#'+$id).fu_popover($options);
	});

	function openAnimationBox(initialRun, box) {
		var $this = $(this);
		var $id = $this.attr('id');

		if( initialRun === true ) {
			var $sections = ['input[id*="_rl_button"]','textarea[id*="_rl_desc"]','input[id*="_rl_subtitle"]','input[id*="_rl_title"]'];

			if( box === true ) {
				$.each($sections, function(key, value){
					var $field = $(value);

					$field.each(function(){
						var $this = box.find(value)
						var $id = $this.attr('id');

						if( $id.search('anm') == -1 ) {
							animationSectionSwitch($id , $this);
						}
					});
				});

			} else {

				$('#cmb2-metabox-_rl_highlights_box').find('.postbox').each(function(){
					var $sec = $(this);

					$.each($sections, function(key, value){
						var $field = $sec.find(value);

						$field.each(function(){
							var $this = $(this);
							var $id = $this.attr('id');

							if( $id.search('anm') == -1 ) {
								animationSectionSwitch($id , $this);
							}
						});
					});
				});
			}

		} else {

			if( $id.search('anm') !== -1 ) {
				return false;
			}

			animationSectionSwitch($id , $this);
		}
	}

	function animationSectionSwitch(id, self) {
		$id = id;
		$this = self;

		if( $id.search('subtitle') !== -1 && $this.val() !== '' ) {
			$this.closest('.postbox').find('.rolo-subtitle-section-ops').addClass('active-box');
		} else if ( $id.search('subtitle') !== -1 && $this.val() == '' ) {
			$this.closest('.postbox').find('.rolo-subtitle-section-ops').removeClass('active-box');
		} else if( $id.search('title') !== -1 && $id.search('subtitle') == -1 && $this.val() !== '' ) {
			$this.closest('.postbox').find('.rolo-title-section-ops').addClass('active-box');
		} else if ( $id.search('title') !== -1 && $id.search('subtitle') == -1 && $this.val() == '' ) {
			$this.closest('.postbox').find('.rolo-title-section-ops').removeClass('active-box');
		}  else if( $id.search('desc') !== -1 && $this.val() !== '' ) {
			$this.closest('.postbox').find('.rolo-desc-section-ops').addClass('active-box');
		} else if ( $id.search('desc') !== -1 && $this.val() == '' ) {
			$this.closest('.postbox').find('.rolo-desc-section-ops').removeClass('active-box');
		} else if( ( $id.search('button1') !== -1 || $id.search('button2') !== -1 ) && $this.val() !== '' ) {
			$this.closest('.postbox').find('.rolo-buttons-section-ops').addClass('active-box');
		} else if ( ( $id.search('button1') !== -1 || $id.search('button2') !== -1 ) && $this.val() == '' ) {
			$this.closest('.postbox').find('.rolo-buttons-section-ops').removeClass('active-box');
		}
	}

	function show_hide(el) {
		var $show = el.data('show');
		var $hide = el.data('hide');

		if( typeof $hide != 'undefined' && el.parent().hasClass('active') ) {
			var $hideEls = $hide.split(',');

			$.each($hideEls, function(key, val){
				$(val).removeClass('vis');

				if( ! $(val).hasClass('hidden') ) {
					$(val).addClass('hidden');
				}
			});
		}

		if( typeof $show != 'undefined' && el.parent().hasClass('active') ) {
			var $showEls = $show.split(',');

			$.each($showEls, function(key, val){
				$(val).addClass('vis');
			});
		}
	}

	function layout() {
		var $id = $('.layout-select').find('.toggle:checked').attr('id');

		$('#normal-sortables').find('.vis').removeClass('vis');

		$('#_'+$id).addClass('vis');
	}

});


jQuery(document).ready(function($){
	// initialize color picker
    if( $('.color').length ) {
		$('.color').alphaColorPicker();
	}
});