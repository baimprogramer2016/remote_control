(function ($) {
  'use strict';
  $(function () {
    var body = $('body');
    var contentWrapper = $('.content-wrapper');
    var scroller = $('.container-scroller');
    var footer = $('.footer');
    var sidebar = $('.sidebar');

    //Add active class to nav-link based on url dynamically
    //Active class can be hard coded directly in html file also as required

    function addActiveClass(element) {
      // console.log(current)
      if (current === "") {
        //for root url
        if (element.attr('href').indexOf("index.html") !== -1) {
          element.parents('.nav-item').last().addClass('active');
          if (element.parents('.sub-menu').length) {
            element.closest('.collapse').addClass('show');
            element.addClass('active');
          }
        }
      } else {
        let fullpage = location.search;
        var urlParams = new URLSearchParams(fullpage);
        var pagecurrenct = urlParams.get('page');


        var menuid = document.getElementById(pagecurrenct);

        if (pagecurrenct === 'chart-power' || pagecurrenct === 'chart-water' || pagecurrenct === 'chart-compressor') {
          var dahsboardmenu = document.getElementById("dashboard");
          dahsboardmenu.classList.add("active");
        } else {
          menuid.classList.add("active");
        }

        if (pagecurrenct === "schedule-ac" || pagecurrenct === "schedule-lighting" || pagecurrenct === 'setting-day'  || pagecurrenct === 'schedule-compressor') {
          var uilighting = document.getElementById('ui-lighting');
          uilighting.classList.add("show");

        }

        if (pagecurrenct === "group-setting-ac" || pagecurrenct === "group-setting-lighting" || pagecurrenct === "group-setting-compressor" ) {
          var uilighting = document.getElementById('ui-remote');
          uilighting.classList.add("show");

        }
   
        if (pagecurrenct === "report-compressor" || pagecurrenct === "report-water-distribution" || pagecurrenct === "report-water" || pagecurrenct === "report-power-house-distribution" || pagecurrenct === "report-power-house") {
          var uireport = document.getElementById('ui-report');
          uireport.classList.add("show");

        }


        //for other url
        // if (element.attr('href').indexOf(current) !== -1) {
        //   element.parents('.nav-item').last().addClass('active');
        //   if (element.parents('.sub-menu').length) {
        //     // element.closest('.collapse').addClass('show');
        //     element.addClass('active');
        //   }
        //   if (element.parents('.submenu-item').length) {
        //     element.addClass('active');
        //   }
        // }
      }
    }

    var current = location.pathname.split("/").slice(-1)[0].replace(/^\/|\/$/g, '');
    $('.nav li a', sidebar).each(function () {
      var $this = $(this);
      addActiveClass($this);
    })

    $('.horizontal-menu .nav li a').each(function () {
      var $this = $(this);
      addActiveClass($this);
    })

    //Close other submenu in sidebar on opening any

    sidebar.on('show.bs.collapse', '.collapse', function () {
      sidebar.find('.collapse.show').collapse('hide');
    });


    //Change sidebar and content-wrapper height
    applyStyles();

    function applyStyles() {
      //Applying perfect scrollbar
      if (!body.hasClass("rtl")) {
        if ($('.settings-panel .tab-content .tab-pane.scroll-wrapper').length) {
          const settingsPanelScroll = new PerfectScrollbar('.settings-panel .tab-content .tab-pane.scroll-wrapper');
        }
        if ($('.chats').length) {
          const chatsScroll = new PerfectScrollbar('.chats');
        }
        if (body.hasClass("sidebar-fixed")) {
          var fixedSidebarScroll = new PerfectScrollbar('#sidebar .nav');
        }
      }
    }

    $('[data-toggle="minimize"]').on("click", function () {
      if ((body.hasClass('sidebar-toggle-display')) || (body.hasClass('sidebar-absolute'))) {
        body.toggleClass('sidebar-hidden');
      } else {
        body.toggleClass('sidebar-icon-only');
      }
    });

    //checkbox and radios
    $(".form-check label,.form-radio label").append('<i class="input-helper"></i>');

    //fullscreen
    $("#fullscreen-button").on("click", function toggleFullScreen() {
      if ((document.fullScreenElement !== undefined && document.fullScreenElement === null) || (document.msFullscreenElement !== undefined && document.msFullscreenElement === null) || (document.mozFullScreen !== undefined && !document.mozFullScreen) || (document.webkitIsFullScreen !== undefined && !document.webkitIsFullScreen)) {
        if (document.documentElement.requestFullScreen) {
          document.documentElement.requestFullScreen();
        } else if (document.documentElement.mozRequestFullScreen) {
          document.documentElement.mozRequestFullScreen();
        } else if (document.documentElement.webkitRequestFullScreen) {
          document.documentElement.webkitRequestFullScreen(Element.ALLOW_KEYBOARD_INPUT);
        } else if (document.documentElement.msRequestFullscreen) {
          document.documentElement.msRequestFullscreen();
        }
      } else {
        if (document.cancelFullScreen) {
          document.cancelFullScreen();
        } else if (document.mozCancelFullScreen) {
          document.mozCancelFullScreen();
        } else if (document.webkitCancelFullScreen) {
          document.webkitCancelFullScreen();
        } else if (document.msExitFullscreen) {
          document.msExitFullscreen();
        }
      }
    })
  });
})(jQuery);