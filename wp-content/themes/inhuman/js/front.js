jQuery(document).ready(function($) {

  "use strict";

  let handleShowSpam = function(e) {
      e.preventDefault();
      $(this).parent().hide().next().show();
		  $('.all-posts').isotope('layout');
  }
  $(".spam-shield a").click(handleShowSpam);

  $(window).scroll(function() {
    // FIXME: incorrectly removes smaller on secondary pages after scrolling down & back up
    // 234 is the height of the extended header/hero
    (window.pageYOffset || document.documentElement.scrollTop) > 234?
      $("header").addClass("smaller") :
      $("header").removeClass("smaller");
  });

  var isotope_opts = {
    itemSelector: '.card',
    layoutMode: 'masonry',
    masonry: {
      columnWidth: '.grid-sizer',
      gutter: '.gutter-sizer',
      percentPosition: true
    }
  };
  $('.featured-posts').isotope(isotope_opts);
  $('.all-posts').isotope(isotope_opts);

  let form = $('#add-screenshot-form');
  $(form).submit(function(event) {
    event.preventDefault();

    if (!$('#php_data_user_display_name').val()) {
      // user not logged in
      localStorage.setItem('addScreenshotAttempt', $('#add-screenshot-url').val());
      $('#a0LoginButton').click();
      return;
    }

    let formData = $(form).serializeForm();
    formData.action = 'inhuman_add_screenshot';
    formData.nonce = $('#php_data_nonce').val();

    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val(),
      data: formData
    })
      .done(function(res) {
        if ("0" === res) {
          alert("Couldn't submit screenshot, try again later");
        }
        location.reload();
      })
      .fail(function(err) {
        alert("Couldn't submit screenshot: " + err);
      });
  });

  var url = localStorage.getItem('addScreenshotAttempt');
  if (url) {
    localStorage.removeItem('addScreenshotAttempt');
    $('#add-screenshot-url').val(url);
    $(form).submit();
  }  

  
  $('.all-posts').parent().append('<span class="load-more"></span>');
	var loadmore = $('.container .load-more');
	var page = 2;
	var loading = false;
	var scrollHandling = {
	    allow: true,
	    reallow: function() {
	        scrollHandling.allow = true;
	    },
	    delay: 400 //(milliseconds) adjust to the highest acceptable value
	};

	$(window).scroll(function() {
		if(!loading && scrollHandling.allow) {
			scrollHandling.allow = false;
			setTimeout(scrollHandling.reallow, scrollHandling.delay);

      if ($(loadmore).isInViewport({tolerance: 200})) {
        console.log('loading more... page: ' + page);
				loading = true;
				var data = {
					action: 'ajax_load_more',
					paged: page
				};
				$.post($('#php_data_ajax_url').val(), data, function(res) {
					if(res.success) {
            var $html = $(res.data)
				    $('.all-posts').append($html).find(".spam-shield a").click(handleShowSpam);
				    $('.all-posts').isotope('appended', $html);
						page = page + 1;
						loading = false;
					} else {
						console.log('failed to load more content: ' + res);
					}
				}).fail(function(xhr, textStatus, e) {
					console.log(xhr.responseText);
				});

			}
		}
  });

  $("#verify-submit").click(function(e) {
    e.preventDefault();
  	var data = {
		  action: 'inhuman_user_verify_token',
      token: $("#verify-token").val()
	  };
    $.ajax({
      type: 'POST',
      url: $('#php_data_ajax_url').val() + "?action=inhuman_user_verify_token",
      data: JSON.stringify(data),
      contentType: "application/json"
    })
    .done(function(res) {
      if (res.success) {
        alert("Email verified!");
        window.location = "/";
      } else {
        alert("Email verification failed: " + res.error);
      }
    })
    .fail(function(err) {
      console.log("Error verifying token: " + JSON.stringify(err));
    });
  });
  if ("/verify/" === window.location.pathname) {
    var params = new URLSearchParams(document.location.search.substring(1));
    var token = params.get("token");
    $("#verify-token").val(token);
    $("#verify-submit").trigger("click");
  }

});