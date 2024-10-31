/*=========================================
                SHORTCODE
==========================================*/          
(function($) {

  var Grps = names;

  tinymce.create('tinymce.plugins.rl_slider', {
    init: function(ed, url) {
      ed.addButton('rl_slider', {
        title: 'Rolo Slider',
        icon: 'tf-n dashicons-before dashicons-slides',
        cmd: 'rl_slider_cmd'        
      });
 
      ed.addCommand('rl_slider_cmd', function() {
        ed.windowManager.open(
          //  Window Properties
          {
            file: url + '/../../core/rolo-insert.html',
            title: 'Insert Rolo Slider',
            width: 370,
            height: 350,
            inline: 1
          },
          //  Windows Parameters/Arguments
          {
            editor: ed,
            groups: Grps,
            jquery: $ // PASS JQUERY
          }
        );
      });
    }
  });
  tinymce.PluginManager.add('rl_slider', tinymce.plugins.rl_slider);
})(jQuery);
