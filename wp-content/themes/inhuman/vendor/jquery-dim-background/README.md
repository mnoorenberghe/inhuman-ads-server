jquery-dim-background
=====================
[![Build Status](https://travis-ci.org/andywer/jquery-dim-background.svg?branch=master)](https://travis-ci.org/andywer/jquery-dim-background) [![Bower version](https://badge.fury.io/bo/jquery-dim-background.svg)](http://badge.fury.io/bo/jquery-dim-background)
 [![Coverage Status](https://img.shields.io/coveralls/andywer/jquery-dim-background.svg)](https://coveralls.io/r/andywer/jquery-dim-background?branch=master)

jQuery plugin to dim the current page except for some user-defined top elements.

Usage
-----

Include the script in your website first. Add this script tag after your jQuery inclusion.

```html
<script type="text/javascript" src="http://andywer.github.io/jquery-dim-background/jquery.dim-background.min.js"></script>
```


Usage is simple. You can dim your website, but keep some elements on top of the curtain:

```html
<script type="text/javascript">
    $('.myElements').dimBackground();
</script>
```


To switch back to normal:

```html
<script type="text/javascript">
    $('.myElements').undim();
    // - or -
    $.undim();
</script>
```


You can also provide a callback function that is called when the animation completes and you can overwrite default options:

(You may find the available options in the `jquery.dim-background.js` file. Have a look at `$.fn.dimBackground.defaults`)

```html
<script type="text/javascript">
    $('.myElements').dimBackground({
        darkness : 0.8            // 0: no dimming, 1: completely black
    }, function() {
        // do something
    });
</script>
```

Demo
----

Have a look at a [basic example](http://andywer.github.io/jquery-dim-background/demo/basic.html) that shows what this plugin does.


License
-------

This plugin is published under the MIT license. See [license](https://raw.github.com/andywer/jquery-dim-background/master/LICENSE.txt).

Have a lot of fun!
