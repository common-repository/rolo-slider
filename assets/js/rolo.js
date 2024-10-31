jQuery(document).ready(function($){
    var $slider = jQuery(".rolo_slider");
    var currentWidth = $(window).width();

   if( $slider.length ) {
      $slider.each(function(){
         var $thisSlider = $(this);
         var $transition = $thisSlider.data('anm'); 
         var $autp = $thisSlider.data('autp');
         var $hasBar = $thisSlider.data('hsb');
         var $height = $thisSlider.data('hgh');
         var $scroll = $thisSlider.data('scr');
         var $slideSpeed = $thisSlider.data('slsp') != '' ? parseInt($thisSlider.data('slsp'),10) : 300;
         var $bullets = $thisSlider.data('bul');
         var $autps = $thisSlider.data('autps');
         var $autst = $thisSlider.data('autst');
         var $forcew = $thisSlider.data('fw');
         if( 'on' === $autp || '' == $autp ) $autp = parseInt($autps,10);
         else $autp = false;
         if( 'off' === $autst || '' == $autst ) $autst = false;
         else $autst = true;
         if( 'on' === $bullets ) $bullets = true;
         else $bullets = false;
         if( 'on' === $autps ) $autps = true;
         else $autps = false;
         if( 'yes' === $hasBar ) $hasBar = true;
         else $hasBar = false;
         var $autps = $thisSlider.data('autps');
         var time = $autps / 1000;
      	 var $Init = 0;
      	 var $kbTime = (time-0.3)+'s, '+time*3+'s';
         var $firstLoad = true;
         if( 'fade' === $transition ) {
             $kbTime = (time+1.1)+'s, '+time*3+'s';
         }

      	 var $progressBar,
      	     $bar, 
      	     $elem, 
      	     isPause, 
      	     tick,
      	     percentTime;

          var $options =  {
             lazyLoad : true,
             autoPlay: $autp,
             navigation : false, // Show next and prev buttons
             pagination: $bullets,
             slideSpeed : $slideSpeed,
             paginationSpeed : 400,
             rewindSpeed : 500,
             singleItem:true,
             stopOnHover : $autst,
             transitionStyle : $transition,
             afterInit : onInit,
            // afterMove : moved,
             afterAction: setUpRolo,
             startDragging : pauseOnDragging
          };

          $thisSlider.owlCarousel($options);
          $thisSlider.on('click', '.slider-arrow', sliderNav);

          if( $forcew == 'yes' ) {
            forceWidth();
            $(window).on('resize', forceWidth);
          }
       
          var $start = new Date().getTime(),  
          $time = 0; 


          function instance()  
          {  
              $time += 100;   
              var diff = (new Date().getTime() - $start) - $time;  
              window.setTimeout(instance, (100 - diff));  
              interval();
          }  
          var $d; 


      	 //Init progressBar where elem is $("#owl-demo")
          function onInit(elem){ 
            $slider.find('.owl-item').eq(0).addClass('zoomed'); 
            $elem = elem; 
            // build progress bar elements
            // if autoplay is enabled
           buildProgressBar();
            //start counting
             //if( $autp ) start();
         // pre init action
          }
       
          //create div#progressBar and div#bar then prepend to $("#owl-demo")
          function buildProgressBar(){ 
            $progressBar = $("<div>",{
              id:"progressBar"
            });
            $bar = $("<div>",{
              id:"bar"
            });
            $navs = '<div class="slider-arrow arrow-left"><span></span></div><div class="slider-arrow arrow-right"><span></span></div>';
            $mouse = '<span class="slider-scrolling"></span>';
            if( $autp && $hasBar ) $progressBar.append($bar).prependTo($elem); 
            if( 'on' == $scroll ) $elem.append($mouse);
            $elem.append($navs);
          }
       
          function start() {
            //reset timer
            percentTime = 0;
            isPause = false;
            $d = window.setTimeout(instance, 100);
          };
       
          function interval() {
            if(isPause === false){
              percentTime += 10 / time;
              $bar.css({
                 width: percentTime+"%"
               });
              //if percentTime is equal or greater than 100
              if(percentTime >= 100){
                //slide to next item 
                $elem.trigger('owl.next')
              }
            }
          }
       
          //pause while dragging 
          function pauseOnDragging(){
            if( $autst ) isPause = true;
          }
       
          //moved callback
          function moved(){
            if( $autp ) {
              //clear interval
              clearTimeout($d);
              //start again
              start();
            }
          }
       
          //make pause on mouseover 
          $thisSlider.on('mouseover', '.owl-item', function(){
            pauseOnDragging()
          });
          $thisSlider.on('mouseout', '.owl-item', function(){
            isPause = false;
          });

          function setUpRolo() { 
            var $zoom = true;
            var $slideIndex = this.owl.currentItem;
            var $slide = $thisSlider.find('.owl-item').eq($slideIndex);
            var $slides = $thisSlider.find('.owl-item');
            var $args = getArguments($slide);
            var $hasMeta = false;
          	if( $slide.find('.slider-meta-wrap').length ) $hasMeta = true;  
            if( $zoom && $hasMeta && $firstLoad ) {
               var $sl = $thisSlider.find('.owl-item').eq(0);
               resetVals($sl, true);
               $firstLoad = false;
            }
          	if( $zoom ) {
          		$thisSlider.find('.owl-item .slide-img').css('transition-duration', $kbTime);
          		$slide.removeClass('zoom-reset').addClass('zoomed').siblings().removeClass('zoomed');
                resetVals($slide);

      	    	if( $hasMeta ) {
      	    		layerAnimation($slide, $args);
      	    	}

      	    }
          }

          // Force slider full width
          function forceWidth() {
            if( $(".owl-carousel").length ) {
                $thisSlider.css('width','100vw');
                var $offset = $thisSlider.closest('.rolo_wrapper').offset().left;
                $thisSlider.css('left', -$offset);
                var $owldata = $(".owl-carousel").data('owlCarousel');
                $owldata.updateVars();
            }
          }


          function resetVals($slide, $firstLoad){
              if( $firstLoad ) {
                  $args = getArguments($slide);
                  layerAnimation($slide, $args, true);
              } else {
                  $slide.siblings().addClass('zoom-reset');
                  $slide.siblings().each(function(){
                      var $t = $(this);
                      $args = getArguments($t);
                      layerAnimation($t, $args, true);
                  });
              }

          }

          // get the slide arguments
          function getArguments(slide) {
             var $slide = slide;
             var $img = $slide.find('.slide-img');
             var $time = $img.data('tanmdur');
             var val = $img.data('tanmv');
             var $del = $img.data('tanmdel');
             var $time2 = $img.data('stanmdur');
             var val2 = $img.data('stanmv');
             var $del2 = $img.data('stanmdel');
             var $time3 = $img.data('danmdur');
             var val3 = $img.data('danmv');
             var $del3 = $img.data('danmdel');
             var $time4 = $img.data('banmdur');
             var val4 = $img.data('banmv');
             var $del4 = $img.data('banmdel');
             var $name = $img.data('tanm');
             var $name2 = $img.data('stanm');
             var $name3 = $img.data('danm');
             var $name4 = $img.data('banm');
             var $args = {
               time: $time,
               time2: $time2,
               time3: $time3,
               time4: $time4,
               name: $name,
               name2: $name2,
               name3: $name3,
               name4: $name4,
               val: val,
               val2: val2,
               val3: val3,
               val4: val4,
               del: $del,
               del2: $del2,
               del3: $del3,
               del4: $del4
            }

            return $args;
          }

          function applyAnimation(el, args, reset) { 
             var $name = args.name;
             var $del = args.del; 
             $del = parseInt($del,10);
             var $time = args.time;
             var $val = args.val;
             var $op = 0;
             if( !reset ) { 
               $val = '';
               $op = 1;
             } else {
                $time = 0;
                $del = 0;
             }
             switch($name){
               case 'fade':
                 el.delay($del).animate({opacity: $op}, $time);
               break;
               case 'slideup': 
                 el.delay($del).animate({opacity: $op, top: $val}, $time);
               break;
               case 'slidedwn':
                 el.delay($del).animate({opacity: $op, bottom: $val}, $time);
               break;
               case 'slidel':
                 el.delay($del).animate({opacity: $op, right: $val}, $time);
               break;
               case 'slider':
                 el.delay($del).animate({opacity: $op, left: $val}, $time);
               break;
               case 'scaleup':
                  if( reset )
                    el.delay($del).animate({opacity: $op, fontSize: '0.7em'}, $time);
                  else
                    el.delay($del).animate({opacity: $op, fontSize: '1em'}, $time);
               break;
               case 'scaledwn':
                  if( reset )
                    el.delay($del).animate({opacity: $op, fontSize: '1.4em'}, $time);
                  else
                    el.delay($del).animate({opacity: $op, fontSize: '1em'}, $time);
               break;
             }
          }


          // apply animations to the layers
          function layerAnimation(slide, args, reset) { 
          	$slide = slide;
            var $args1 = {
               name: args.name != '' ? args.name : '',
               del: args.del != '' ? args.del : 460,
               time: args.time != '' ? args.time : 500,
               val: args.val != '' ? args.val : 45
            }
            var $args2 = {
               name: args.name2 != '' ? args.name2 : '',
               del: args.del2 != '' ? args.del2 : 760,
               time: args.time2 != '' ? args.time2 : 500,
               val: args.val2 != '' ? args.val2 : 45
            }
            var $args3 = {
               name: args.name3 != '' ? args.name3 : '',
               del: args.del3 != '' ? args.del3 : 980,
               time: args.time3 != '' ? args.time3 : 500,
               val: args.val3 != '' ? args.val3 : 45
            }
            var $args4 = {
               name: args.name4 != '' ? args.name4 : '',
               del: args.del4 != '' ? args.del4 : 1100,
               time: args.time4 != '' ? args.time4 : 500,
               val: args.val4 != '' ? args.val4 : 45
            }
            var $el1 = $slide.find('.slider-meta-wrap').find('h3');
            var $el2 = $slide.find('.slider-meta-wrap').find('h4');
            var $el3 = $slide.find('.slider-meta-wrap').find('p');
            var $el4 = $slide.find('.slider-meta-wrap').find('.slider-buttons');

            if( reset ) {
               applyAnimation($el1, $args1, true);
               applyAnimation($el2, $args2, true);
               applyAnimation($el3, $args3, true);
               applyAnimation($el4, $args4, true);
            } else {  
               applyAnimation($el1, $args1);
               applyAnimation($el2, $args2);
               applyAnimation($el3, $args3);
               applyAnimation($el4, $args4);
            }

          }

          // custom navigation arrows
          function sliderNav() {
            var $this = $(this);
            if( $this.hasClass('arrow-right') ) $thisSlider.trigger('owl.next');
            else $thisSlider.trigger('owl.prev');
          }
      });
    }
});