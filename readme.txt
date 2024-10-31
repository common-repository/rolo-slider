=== Rolo Slider ===
Contributors: pressfore
Donate link:
Tags: slider, responsive slider, layer slider, ken burns, ken burns slider, animated slider, image slider
Requires at least: 3.9.0
Tested up to: 4.9.6
Stable tag: 4.6
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Best free WordPress slider for creating stunning slides with ken burns effect and animated layers. Add images, heading ,subheading, and more!

== Description ==

**Rolo Slider** is Best Responsive Slider for creating stunning slides with ken burns effect and animated layers. Add images, heading ,subheading, description and buttons inside each slide. Select the entrance/exit animation for each layer, and edit slides and options inside simple to use native user interface. 

It comes in 2 layouts - Default one which is layered - you can add headinngs, description and buttons into slides, and Responsive Images layout - just simple images slider 

> [Slider Demo](http://demo.pressfore.com/index.php?item=rolo-slider&type=plugins) | [Documentation](http://docs.pressfore.com/rolo-slider/)

Demos with addons :

> [Controller Addon Demo](http://demo.pressfore.com/index.php?item=rolo-slider&type=plugins&page=rolo-controller-addon) | [Layer Colors Addon Demo](http://demo.pressfore.com/index.php?item=rolo-slider&type=plugins&page=layer-colors-addon) | [Posts Slider Addon Demo](http://demo.pressfore.com/index.php?item=rolo-slider&type=plugins&page=posts-slider-addon) | [Products Slider Addon Demo](http://demo.pressfore.com/index.php?item=rolo-slider&type=plugins&page=woocommerce-products-slider-addon)

**Video Preview**

[youtube https://www.youtube.com/watch?v=WfxwUrSyN9Q]

**Main Features:**

*   Responsive
*   Ken Burns Effect
*   Supports Full Width Mode
*   Easy to use
*   Animated Layers
*   Simple To Customize
*   Demo Data Import
*   Import and Export option for sliders
*   Modern Look
*   Comes With 2 Layouts
*   Simple integration via Shortcode

Check out our **[Addons](http://pressfore.com/item-category/addons/)** for even more options, like adding full height, styling individual layers, dynamic inclusion of posts and products into slider and more.

**Addons Features:**
*   Full Height
*   Ken Burns Effect Direction Per Slide
*   Zooming factor for ken burns per slide
*   Ken Burns Effect Moving factor per slide
*   Customize Layer colors per slide
*   2 Additional Slide Transitions
*   Dynamic posts listing
*   Dynamic woo commerce products listing
*   And more ++

**Want to see some new feature ?** Propose us a feature [Here](http://pressfore.com/feature-proposal/) . We are looking forward to hear from you!


== Installation ==

Rolo Slider can be installed in two different ways:

*  Via WordpPress uploader
*  or via FTP Client

1- Steps to follow to install plugin via WordPress uploader

*   go to WordPress admin panel and click on "plugins" from the dashboard menu.
*   Then from Plugins -> Add New -> Upload -> Choose file -> and then choose rolo-slider.zip and press Open-> Install Now -> Activate Plugin

2- Steps to follow to install plugin via FTP

*   Unzip the file
*   Upload unziped rolo-slider folder to your WordPress plugins directory (/wp-content/plugins/)
*   Activate from WordPress menu Plugins section

== Screenshots ==
1. Import/Export Page
2. Options UI
3. Options Tooltip
4. One Click Demo Import

== Frequently Asked Questions ==

= [Q] Why my images are not scaling on small screens in default layout =

[A] Slider background images for **default layout** are set with css – background-size: cover and it is best and usual way for setting background for containers because in that way image will be forced to cover entire container regardless of the image size. So, it is important to know that most of the time, parts of the slide background won’t be visible. The actual visible portion will vary depending on screen orientation and width/height aspect ratio. For instance, the common aspect ratio of portrait orientated tablets is 3:4 (iPad), and for desktop monitors it is 16:9 (full-HD resolution). In all instances the background is scaled to be as large as possible so that the slider area is completely covered by the background image. In simple words, some background clipping must occur, while the “content-safe” area is the very center of each slide – this area is always visible. Keeping this safe area in mind, if you are using slider background as an important part of your visual message, like an image showing your product for example, make sure that product is placed within that safe area so it won’t be clipped out.

= [Q] Why slider height is not applied for Responsive Images layout =

[A] **Responsive images layout** is taking the height of the tallest image. This way it will ensure the proper scaling of the images across devices, while keeping the proper proportions. This layout do not support layered elements, because the elements wouldn't fit in this layout, as on the smaller screens images can get really small in height. To add layers use **Default layout** which is displaying images differently on small devices, using background protpery. For more details how it works read the answer for the first question above.

== Credits ==

* [Input Range Styles](http://danielstern.ca/range.css)
* [PopoverJs](https://github.com/popoverjs)
* [Alpha Color Picker](https://github.com/BraadMartin/components/tree/master/customizer/alpha-color-picker)
* [WordPress Importer](https://wordpress.org/plugins/wordpress-importer/)
* [CMB2](https://github.com/WebDevStudios/CMB2)
* [Owl Carousel](http://owlgraphic.com/owlcarousel/)


== Changelog ==
= 1.0.9 =
* Improved PHP7 compatibility for import functionality
= 1.0.8 =
* Fixed paths to some images in rolo.css file
= 1.0.7 =
* Fixed some text strings which couldn't be translated
* Updated translation POT file
= 1.0.6 =
* Fixed plugin text domain
* Fixed translation POT file
= 1.0.5 =
* Overall improvement of transitions for cross browser support
= 1.0.4 =
* Fixed horizontal scrollbar on some instances
* Improved Force Full screen option
* Extended autoplay scroll option up to 15s
* Included new hooks
= 1.0.3 =
* Improved code quality
= 1.0.2 =
* Improved backend js
= 1.0.1 =
* Added new pages
* Minor Bug Fixes
= 1.0.0 =
* Added Import page
* Added Export page
* Added Demo Data page
* Added Buttons hover text color
* Added Buttons hover background color
* Added Ken Burns to the "responsive images" layout
* Improved options UI
* Organized slider options into tabs
* Refactored and optimized code
= 0.5.1 =
* Fixed multiple sliders per page issue
= 0.5 =
* Added WP 4.7 compatibility
* Increased circle pagination size
* Increased slider height on mobile devices
= 0.4.1 =
* Fixed inverted button styles
* Fixed buttons styles when captions are included
* Fixed conditional on admin submenu pages
= 0.4 =
* Added captions background color
* Added captions text color
* Added transparent captions option per slide
* Improved styles
= 0.3.6 =
* Added missed name parameter in generated shortcode
= 0.3.5 =
* Fixed duplicate slides issue for "Responsive Images" layout
= 0.3.4 =
* Fixed Slider not showing issue
* Added unistall function
= 0.3.3 =
* Fixed rolo force full width on screen resize
* Fixed js error
= 0.3.2 =
* Added Force Full width option
* Added FadeUp slide transition
= 0.3.1 =
* Integrated alt tags for images
* Removed Ken Burns effect from "responsive image" slider layout
= 0.3 =
* Added "Responsive Images" layout
* Fixed doubled slider issue
* Fixed  arrow background color on hover
= 0.2 =
* Improved compatibilty with some themes that are including owl carousel
* Added pot file
= 0.1.2 =
* Replaced color picker to include custom color input field
= 0.1.1 =
* Improved Mouse scroll animation
* Slower fade transition between slides
* Added feature proposal link inside the plugin
* Added link to online documentation inside the plugin
= 0.1.0 =
* Initial Release
