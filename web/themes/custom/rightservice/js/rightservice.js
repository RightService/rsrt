(function ($, Drupal, drupalSettings, window) {
  Drupal.behaviors.rightservice = {
    attach: function (context, settings) {
      $('.ext').on('click', function (e) {
        window.open($('.ext').attr('href'), '_blank', 'height=720,width=960,');
        e.preventDefault();
      });

      //colorbox
      if (!$.isFunction($.colorbox)) {
        return;
      }
      // TODO: Refactor variable's names
      $.urlParams = function (url) {
        let p = {'title': url},
          e,
          a = /\+/g,  // Regex for replacing addition symbol with a space
          r = /([^&=]+)=?([^&]*)/g,
          d = function (s) {
            return decodeURIComponent(s.replace(a, ' '));
          },
          q = url.split('?');
        while (e = r.exec(q[1])) {
          e[1] = d(e[1]);
          e[2] = d(e[2]);
          switch (e[2].toLowerCase()) {
            case 'true':
            case 'yes':
              e[2] = true;
              break;
            case 'false':
            case 'no':
              e[2] = false;
              break;
          }
          if (e[1] === 'width') {
            e[1] = 'innerWidth';
          }
          if (e[1] === 'height') {
            e[1] = 'innerHeight';
          }
          p[e[1]] = e[2];
        }

        return p;
      };
      
        $('.field-name-field-url a, a.ext').each(function () {
          const $element = $(this);
        
          // Check if the 'init-colorbox-load' class has been added before
          if (!$element.data('init-colorbox-load')) {
            // Add the class and data attribute
            $element.addClass('init-colorbox-load').data('init-colorbox-load', true);
        
            const settings = {
              "iframe": true,
              "width": "1024px",
              "height": "768px",
              "opacity": "0.85",
              "current": "{current} of {total}",
              "previous": "\u00ab Prev",
              "next": "Next \u00bb",
              "close": "Close",
              "maxWidth": "98%",
              "maxHeight": "98%",
              "fixed": true,
              "mobiledetect": true,
              "mobiledevicewidth": "480px"
            };
        
            const params = $.urlParams($element.attr('href'));
            $element.colorbox($.extend({}, settings, params));
          }
        });
        

      // TODo RESTRICT
      const restrictDOM = function () {
        const font = '<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700" rel="stylesheet">';

        $('body').append(font);
      };

      const restrictMapSearch = function () {
        let $blockRegion = $('.block-region-content.first');
        let map = {
          $wrapper: $('div.select-county'),
          $title: $('div.select-county h2'),
          $toggleWrapper: $('ul.select-county_toggle'),
          $togglePoints: [
            $('li.county-map'),
            $('li.county-list')
          ],
          $bodyMap: $('div.select-county_body_map'),
          $bodyList: $('div.select-county_body_list')
        };

        $.each(map.$togglePoints, function () {
          $(this).on('click', function () {
            if (!$(this).hasClass('active')) {
              const type = $(this).data('type');

              $('.select-county_toggle li.active').removeClass('active');
              $(this).addClass('active');
              $('.region-content_body.active').removeClass('active');
              $('.select-county_body_' + type).addClass('active');
            }
            return false;
          });

          map.$toggleWrapper.append($(this))
        });

        map.$wrapper
          .append(map.$title)
          .append(map.$toggleWrapper)
          .append(map.$bodyMap)
          .append(map.$bodyList);

        const $map = $blockRegion.find('.view-header'),
          $listCounty = $blockRegion.find('.views-view-grid.cols-4');
        map.$bodyMap.append($map);
        map.$bodyList.append($listCounty);
        $blockRegion.prepend(map.$wrapper);

        window.updateMap = function () {
          $('.select-county_body_map').append($('<div />', {
            id: 'container',
            class: 'new-map'
          }));

          // TODO: Move to drupal settings or other config
          const data = [
            ['us-fl-131', 0],
            ['us-fl-087', 1],
            ['us-fl-053', 2],
            ['us-fl-051', 3],
            ['us-fl-043', 4],
            ['us-fl-086', 5],
            ['us-fl-037', 6],
            ['us-fl-097', 7],
            ['us-fl-093', 8],
            ['us-fl-071', 9],
            ['us-fl-111', 10],
            ['us-fl-061', 11],
            ['us-fl-085', 12],
            ['us-fl-021', 13],
            ['us-fl-049', 14],
            ['us-fl-055', 15],
            ['us-fl-027', 16],
            ['us-fl-015', 17],
            ['us-fl-009', 18],
            ['us-fl-095', 19],
            ['us-fl-129', 20],
            ['us-fl-133', 21],
            ['us-fl-005', 22],
            ['us-fl-007', 23],
            ['us-fl-107', 24],
            ['us-fl-031', 25],
            ['us-fl-003', 26],
            ['us-fl-019', 27],
            ['us-fl-063', 28],
            ['us-fl-103', 29],
            ['us-fl-057', 30],
            ['us-fl-035', 31],
            ['us-fl-045', 32],
            ['us-fl-065', 33],
            ['us-fl-081', 34],
            ['us-fl-101', 35],
            ['us-fl-115', 36],
            ['us-fl-073', 37],
            ['us-fl-033', 38],
            ['us-fl-113', 39],
            ['us-fl-011', 40],
            ['us-fl-089', 41],
            ['us-fl-077', 42],
            ['us-fl-105', 43],
            ['us-fl-041', 44],
            ['us-fl-067', 45],
            ['us-fl-127', 46],
            ['us-fl-121', 47],
            ['us-fl-083', 48],
            ['us-fl-017', 49],
            ['us-fl-075', 50],
            ['us-fl-059', 51],
            ['us-fl-079', 52],
            ['us-fl-029', 53],
            ['us-fl-117', 54],
            ['us-fl-069', 55],
            ['us-fl-091', 56],
            ['us-fl-001', 57],
            ['us-fl-125', 58],
            ['us-fl-013', 59],
            ['us-fl-123', 60],
            ['us-fl-119', 61],
            ['us-fl-109', 62],
            ['us-fl-099', 63],
            ['us-fl-047', 64],
            ['us-fl-023', 65],
            ['us-fl-039', 66]
          ];

          Highcharts.mapChart('container', {
            chart: {
              map: 'countries/us/us-fl-all'
            },
            title: {
              text: ' '
            },
            legend: {
              enabled: false
            },
            mapNavigation: {
              enabled: false
            },
            colorAxis: {
              min: 0,
              minColor: '#edeff1',
              maxColor: '#b4b9c0'
            },
            exporting: {enabled: false},
            series: [{
              name: 'County',
              data: data,
              states: {
                hover: {
                  color: '#163e71'
                }
              },
              borderColor: '#333',
              dataLabels: {
                enabled: true,
                format: '{point.name}',
                color: '#0f2e4f'
              },
              tooltip: {
                headerFormat: ' ',
                pointFormat: '{point.name}'
              },
              point: {
                events: {
                  click: function () {
                    window.location.pathname = '/servicetypes/' + this.name.toLowerCase();
                  }
                }
              }
            }]
          });
        };
      };

      const restrictLogin = function () {
        let $login = $('.login-anons');

        $login.find('.form-item').each(function () {
          $(this).find('input').attr({
            placeholder: $(this).find('label').text()
          });
          $(this).find('label').remove();
        });

        $(document).off('click.login').on('click.login', '.form-showing', function () {
          $login.toggleClass('form-show');
          return false;
        });
      };

      const changePageTemplate = function () {
        $('.flag-ignore').remove();
        $('.flag-basket').remove();

        $('label:contains("Language")').closest('.form-item').remove();

        $('#tasks a:contains("Translate")').closest('li').remove();

        let $viewFormType = $('#views-exposed-form-service-type-page-1');
        if (!$('body').hasClass('navbar-administration') && $viewFormType.length > 0) {
          $viewFormType.remove();
        }
      };

      const responsiveFeatures = function () {
        const sidebar = '.region.region-sidebar-first.sidebar';
        const $sidebarButton = $('<button />', {
          class: 'sidebar-toggle',
          type: 'button'
        });
        $(sidebar).prepend($sidebarButton);
        $(document).on('click.sidebar', '.sidebar-toggle', function () {
          $(this).closest(sidebar).toggleClass('active');
        })
      };

      const fixServiceTypeCounty = function (county) {
        $('.field--name-field-service-type a').each(function () {
          let href = $(this).attr('href').replace(/(\/service-type\/)(.*)(\/services)/, "$1" + county + "$3");
          $(this).attr('href', href);
        });
      }

      const fixFreqUsedServicesPageCounty = function (county) {
        const FreqUsedServicesPage = 21005;
        if (county && window.location.href.indexOf('node/' + FreqUsedServicesPage) > -1) {
          let $faqTableLink = $('.faq-table a');
          if ($($faqTableLink[0]).attr('href').indexOf('county/') === -1) {
            $faqTableLink.each(function () {
              let href = '/county/' + county + $(this).attr('href');
              $(this).attr('href', href);
            });
          }
        }
      }

      window.selectDefaultCounty = function () {
        const county = $('.default-county-select-modal select:first option:selected').text().toLowerCase();
        document.cookie = 'county=' + county;
        if (window.location.href.indexOf('node') > -1) {
          fixServiceTypeCounty(county);
        }
        fixFreqUsedServicesPageCounty(county);
        $('#popup-message-close').click();
      }

      window.getCookie = function (name) {
        const match = document.cookie.match(new RegExp('(^| )' + name + '=([^;]+)'));
        if (match) {
          return match[2];
        }
      }

      var gtmExecuted = false;
      $(window, context).each(function () {

        if(!gtmExecuted) {

          if (drupalSettings.gaId && navigator.doNotTrack !== '1') {
            (function (i, s, o, g, r, a, m) {
              i['GoogleAnalyticsObject'] = r;
              i[r] = i[r] || function () {
                (i[r].q = i[r].q || []).push(arguments)
              }, i[r].l = 1 * new Date();
              a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
              a.async = 1;
              a.src = g;
              m.parentNode.insertBefore(a, m)
            })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
            ga('create', drupalSettings.gaId, 'auto');
            ga('send', 'pageview');
          }

          if (drupalSettings.gaMeasurementId && navigator.doNotTrack !== '1') {
            (function (w, d, s, l, i) {
              w[l] = w[l] || [];
              w[l].push({
                'gtm.start':
                  new Date().getTime(), event: 'gtm.js'
              });
              var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
              j.async = true;
              j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i;
              f.parentNode.insertBefore(j, f);
            })(window, document, 'script', 'dataLayer', drupalSettings.gaMeasurementId);
            window.dataLayer = window.dataLayer || [];

            function gtag() {
              dataLayer.push(arguments);
            }

            gtag('js', new Date());
            gtag('config', drupalSettings.gaMeasurementId);
          }

          restrictDOM();
          restrictLogin();

          if ($('body').hasClass('path-frontpage')) {
            restrictMapSearch();

            updateMap();
          }
          changePageTemplate();

          responsiveFeatures();

          setTimeout(function () {
            let el = $('.geofieldMap iframe');
            if (el.length) {
              el.each(function () {
                $(this).prop("title", "Google Maps");
              })
            }
          }, 150);

          $('#user-login-form input[name = "name"]').before('<label for="name" class="element-invisible">Username *</label>');
          $('#user-login-form input[name = "pass"]').before('<label for="pass" class="element-invisible">Password *</label>');

          fixFreqUsedServicesPageCounty(window.getCookie('county'));
        }
        
        gtmExecuted = true;
      });

      const $reachField = $('.field--name-field-reach');
      if ($reachField.length > 0) {
        $('.field--name-field-counties').hide();
      }
    }
  }
})(jQuery, Drupal, drupalSettings, window);
