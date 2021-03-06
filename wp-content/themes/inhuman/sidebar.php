<div id="sidebar">
  <div class="sidebar-content">

    <div class="sidebar-top">
      <a class="close-button" href="#sidebar">╳</a>

      <ul class="sidebar-menu">
        <li><a href="<?php echo home_url(); ?>">Home</a></li>
        <?php
          $subscribe = get_page_by_title('Subscribe');
          $excluded_pages = array(
            get_page_by_title('Verify'), $subscribe);
          $excluded_ids = array();
          foreach ($excluded_pages as $page) {
            if ($page != NULL)
              $excluded_ids[] = $page->ID;
          }
          wp_list_pages(array('title_li' => '', 'exclude' => join(',', $excluded_ids))); ?>
        <li><a href="<?php echo get_permalink($subscribe); ?>">Subscribe <i class="fa fa-envelope-o"></i></a></li>
      </ul>

      <hr class="title-divider" />

      <a href="/" class="sitelink"><img class="sidebar-logo" src="<?php tpldir(); ?>/assets/inhuman-logo.svg"></a>
      <div class="poweredby">Powered by<br>Firefox Screenshots</div>

      <div class="icons">
        <a href="https://twitter.com/inhumanads"><img class="icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/icon-Twitter.svg"></a>
        <a href="https://github.com/vthunder/inhuman-ads-server"><img class="icon" src="<?php echo get_bloginfo('template_directory'); ?>/assets/icon-github.svg"></a>
      </div>
    </div>

    <div class="sidebar-bottom">
      <a class="mozlogo" href="https://mozilla.org">
        <img src="<?php echo get_bloginfo('template_directory'); ?>/assets/mozilla-logo/moz-logo-bw-rgb.svg">
      </a>
      <ul>
        <li><a href="https://www.mozilla.org/en-US/about/legal/terms/mozilla/">Terms</a></li>
        <li><a href="https://www.mozilla.org/en-US/privacy/websites/">Privacy Notice</a></li>
        <li><a href="https://www.mozilla.org/en-US/privacy/websites/#cookies">Cookie Policy</a></li>
        <li><a href="https://www.mozilla.org/en-US/about/legal/report-infringement/">Report IP Infringement</a></li>
      </ul>
    </div>
  </div>
</div>
