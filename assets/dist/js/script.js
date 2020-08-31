"use strict";

var sideNavPS = {};
var $window = $(window);

(function ($) {
    var $body = $('body');
    var $loader = $('.dt-loader-container');
    var $root = $('.dt-root');

    // :: Sticky Active Code
    $window.on('scroll', function () {
        if ($window.scrollTop() > 0) {
            $('.header-area').addClass('sticky');
            $('#white-logo').addClass('hidden');
            $('#dark-logo').removeClass('hidden');
        } else {
            $('.header-area').removeClass('sticky');
            $('#dark-logo').addClass('hidden');
            $('#white-logo').removeClass('hidden');
        }
    });

    // :: Gallery Menu Style Active Code
    $('.classy-navbar-toggler').on('click', function () {
        $('.navbarToggler').toggleClass('active');
        $('.classy-nav-container ').toggleClass('breakpoint-on');
        $('.classy-nav-container ').toggleClass('breakpoint-off');
    });

    // :: Gallery Menu Style Active Code
    $('.cross-wrap').on('click', function () {
        $('.navbarToggler').toggleClass('active');
        $('.classy-nav-container ').toggleClass('breakpoint-on');
        $('.classy-nav-container ').toggleClass('breakpoint-off');
    });

    $('li').on('click', function () {
        $('.navbarToggler').removeClass('active');
        $('.classy-nav-container ').addClass('breakpoint-off');
        $('.classy-nav-container ').removeClass('breakpoint-on');
    });

    // :: Accordian Active Code
    

    if ($loader.length) {
        $loader.delay(300).fadeOut('noraml', function () {
            $body.css('overflow', 'auto');
            $root.css('opacity', '1');
            $(document).trigger('loader-hide');
        });
    } else {
        $(document).trigger('loader-hide');
    }

    if ($('#main-sidebar').length) {
        sideNavPS = new PerfectScrollbar('#main-sidebar', {
          wheelPropagation: false
        });
    }

    $('.ps-custom-scrollbar').each(function () {
        new PerfectScrollbar(this, {
          wheelPropagation: false
        });
    });

    if ($.isFunction($.fn.masonry)) {
        var $grid = $('.dt-masonry');
        $grid.masonry({
            // options
            itemSelector: '.dt-masonry__item',
            percentPosition: true
        });        

        $(document).on('layout-changed', function () {
            setTimeout(function () {
                $grid.masonry('reloadItems');
                $grid.masonry({
                    // options
                    itemSelector: '.dt-masonry__item',
                    percentPosition: true
                });
            }, 500);
        });
    }

    var current_path = window.location.href.split('/').pop();
    if (current_path == '') {
        current_path = 'index.php';
    }

  current_path = current_path.replace(".html", ".php");

    var $current_menu = $('a[href="' + current_path + '"]');
    $current_menu.addClass('active').parents('.nav-item').find('> .nav-link').addClass('active');

    if ($current_menu.length > 0) {
        $('.dt-side-nav__item').removeClass('open');

        if ($current_menu.parents().hasClass('dt-side-nav__item')) {
            $current_menu.parents('.dt-side-nav__item').addClass('open selected');
        } else {
            $current_menu.parent().addClass('active').parents('.dt-side-nav__item').addClass('open selected');
        }
    }

    var slideDuration = 150;
    $("ul.dt-side-nav > li.dt-side-nav__item").on("click", function () {
        var menuLi = this;
        $("ul.dt-side-nav > li.dt-side-nav__item").not(menuLi).removeClass("open");
        $("ul.dt-side-nav > li.dt-side-nav__item ul").not($("ul", menuLi)).slideUp(slideDuration);
        $(" > ul", menuLi).slideToggle(slideDuration, function () {
            $(menuLi).toggleClass("open");
        });
    });

    $("ul.dt-side-nav__sub-menu li").on('click', function (e) {
        var $current_sm_li = $(this);
        var $current_sm_li_parent = $current_sm_li.parent();

        if ($current_sm_li_parent.parent().hasClass("active")) {
            $("li ul", $current_sm_li_parent).not($("ul", $current_sm_li)).slideUp(slideDuration, function () {
                $("li", $current_sm_li_parent).not($current_sm_li).removeClass("active");
            });

        } else {
            $("ul.dt-side-nav__sub-menu li ul").not($(" ul", $current_sm_li)).slideUp(slideDuration, function () {
                //$("ul.sub-menu li").not($current_sm_li).removeClass("active");console.log('has not parent');
            });
        }

        $(" > ul", $current_sm_li).slideToggle(slideDuration, function () {
            $($current_sm_li).toggleClass("active");
        });

        e.stopPropagation();
    });

    //Define js
    var axis96 = {
        docBody: $('body'),
        customStyle: null,
        addClass: function (eleRef, eleID, className) {
            jQuery(eleRef).parents(eleID).addClass(className);
        },
        removeClass: function (eleRef, eleID, className) {
            jQuery(eleRef).parents(eleID).removeClass(className);
        },
        sidebar: {
            window: $(window),
            docBody: $('body'),
            drawerRef: jQuery('.dt-sidebar'),
            sidebarToggleHandle: $('[data-toggle=main-sidebar]'),
            foldedHandle: $('[data-handle=folded]'),
            overlay: null,
            enabledFixedSidebar: false,
            enabledFoldedSidebar: false,
            enabledDrawer: false,
            init: function () {
                var sidebar = this;
    
                if (this.drawerRef.hasClass('dt-drawer')) {
                    this.enabledDrawer = true;
                }
    
                var bodyWidth = sidebar.docBody.innerWidth();
                if (bodyWidth < 992) {
                    sidebar.initDrawer();
                } else {
                    sidebar.destroy();
                }
    
                sidebar.window.resize(function () {
                    bodyWidth = sidebar.docBody.innerWidth();
                    if (bodyWidth < 992) {
                        sidebar.initDrawer();
                    } else {
                        sidebar.destroy();
                    }
                });
    
                this.sidebarToggleHandle.on('click', function () {
                    sidebar.toggleFolded();
                });
            },
            initDrawer: function () {
                if (this.docBody.hasClass('dt-sidebar--fixed')) {
                    this.enabledFixedSidebar = true;
                }
    
                if (this.docBody.hasClass('dt-sidebar--folded')) {
                    this.enabledFoldedSidebar = true;
                }
    
                this.docBody.removeClass('dt-sidebar--fixed');
                this.docBody.removeClass('dt-sidebar--folded');
    
                this.drawerRef.addClass('dt-drawer position-left');
            },
            destroy: function () {
                if (!this.enabledDrawer) {
                    this.drawerRef.removeClass('dt-drawer position-left');
                }
    
                if (this.enabledFixedSidebar) {
                    this.docBody.addClass('dt-sidebar--fixed');
                }
    
                if (this.enabledFoldedSidebar) {
                    this.docBody.addClass('dt-sidebar--folded');
                }
            },
            toggleFolded: function () {
                if (!this.drawerRef.hasClass('dt-drawer')) {
                    if (this.docBody.hasClass('dt-sidebar--folded')) {
                        this.sidebarUnfolded();
                        activeLayoutHandle('default');
                    } else {
                        this.sidebarFolded();
                        activeLayoutHandle('folded');
                    }
                }
            },
            sidebarFolded: function () {
                this.docBody.addClass('dt-sidebar--folded');
                $(document).trigger('sidebar-folded');
            },
            sidebarUnfolded: function () {
                this.docBody.removeClass('dt-sidebar--folded');
                $(document).trigger('sidebar-unfolded');
            },
            toggle: function () {
                if (this.drawerRef.hasClass('open')) {
                    this.close();
                } else {
                    this.open()
                }
            },
            open: function () {
                this.drawerRef.addClass('open');
                this.insertOverlay();
                this.sidebarToggleHandle.addClass('active');
            },
            close: function () {
                this.drawerRef.removeClass('open');
                this.overlay.remove();
                this.sidebarToggleHandle.removeClass('active');
            },
            insertOverlay: function () {
                this.overlay = document.createElement('div');
                this.overlay.className = 'dt-backdrop';
                this.drawerRef.after(this.overlay);
    
                var drawer = this;
                var overlayContainer = $(this.overlay);
                overlayContainer.on('click', function (event) {
                    event.stopPropagation();
                    drawer.toggle();
                });
            }
        },
        hoverCard: {
            docBody: $('body'),
            hoverHndle: $('[data-hover=thumb-card]'),
            handleRef: null,
            thumbCard: null,
            init: function () {
                var $this = this;
                this.createHoverCard();
    
                this.hoverHndle.hover(function () {
                    $this.handleRef = $(this);
                    $this.showThumb();
                }, function () {
                    $this.hideThumb();
                });
            },
            showThumb: function () {
                var bodyWidth = this.docBody.outerWidth(true);
    
                if (bodyWidth > 767) {
                    var $this = this;
                    var offset = this.handleRef.offset();
                    var handleWidth = this.handleRef.outerWidth(true);
                    var name = (this.handleRef.data('name')) ? this.handleRef.data('name') : '';
    
                    var innerHtml = '<span class="user-bg-card__info"><span class="dt-avatar-name text-center">' + name + '</span></span>';
    
                    $this.thumbCard.html(innerHtml);
    
                    if (($this.handleRef.data('thumb'))) {
                        $this.thumbCard.css({
                            backgroundImage: 'url(' + $this.handleRef.data('thumb') + ')',
                            backgroundPosition: 'center center',
                            backgroundSize: 'cover'
                        });
                    } else {
                        $this.thumbCard.css({background: 'transparent'});
                    }
    
                    $this.thumbCard.css({
                        left: (offset.left - ((handleWidth + 67.5) / 2)),
                        top: (offset.top - 100),
                        width: 135,
                        height: 90,
                        zIndex: 2
                    });
                    $this.thumbCard.fadeIn();
                }
            },
            hideThumb: function () {
                this.thumbCard.fadeOut();
            },
            createHoverCard: function () {
                var tc = document.createElement('div');
                tc.className = 'card user-bg-card position-absolute bg-primary';
                tc.style.display = 'none';
                this.docBody.append(tc);
                this.thumbCard = $(tc);
            }
        },
        customizer: {
            toggleHandle: $('[data-toggle=customizer]'),
            containerPanel: $('.dt-customizer'),
            overlay: null,
            init: function () {
                var $this = this;
    
                $this.toggleHandle.on('click', function () {
                    $this.toggle();
                });
            },
            toggle: function () {
                if (this.containerPanel.hasClass('open')) {
                    this.close();
                } else {
                    this.open()
                }
            },
            open: function () {
                this.containerPanel.addClass('open');
                this.insertOverlay();
            },
            close: function () {
                this.containerPanel.removeClass('open');
                this.overlay.remove();
            },
            insertOverlay: function () {
                this.overlay = document.createElement('div');
                this.overlay.className = 'dt-backdrop';
                this.containerPanel.after(this.overlay);
    
                var $this = this;
                var overlayContainer = $(this.overlay);
                overlayContainer.on('click', function (event) {
                    event.stopPropagation();
                    $this.toggle();
                });
            }
        },
        quickDrawer: {
            toggleHandle: $('[data-toggle=quick-drawer]'),
            containerPanel: $('.dt-quick-drawer'),
            overlay: null,
            init: function () {
                var $this = this;
    
                $this.toggleHandle.on('click', function () {
                    $this.toggle();
                });
            },
            toggle: function () {
                if (this.containerPanel.hasClass('open')) {
                    this.close();
                } else {
                    this.open()
                }
            },
            open: function () {
                this.containerPanel.addClass('open');
                this.insertOverlay();
            },
            close: function () {
                this.containerPanel.removeClass('open');
                this.overlay.remove();
            },
            insertOverlay: function () {
                this.overlay = document.createElement('div');
                this.overlay.className = 'dt-backdrop';
                this.containerPanel.after(this.overlay);
    
                var $this = this;
                var overlayContainer = $(this.overlay);
                overlayContainer.on('click', function (event) {
                    event.stopPropagation();
                    $this.toggle();
                });
            }
        },
        init: function () {
            this.sidebar.init();
            this.hoverCard.init();
            this.customizer.init();
            this.quickDrawer.init();
            this.initCustomStyle();
        },
        updateStyle: function (style) {
            this.customStyle.innerHTML = style;
        },
        initCustomStyle: function () {
            this.customStyle = document.createElement('style');
            this.docBody.prepend(this.customStyle);
        }
    };

    var dtDrawer = {
        container: $('#dt-notification-drawer'),
        toggleHandle: $('[data-toggle=drawer]'),
        switchHandle: $('[data-switch=drawer]'),
        dismisHandle: $('[data-dismiss=drawer]'),
        init: function () {
            var mdrawer = this;
            this.toggleHandle.on('click', function () {
                mdrawer.activeHandle = $(this);
                mdrawer.toggle();
            });
    
            this.switchHandle.on('click', function () {
                mdrawer.activeSwitch = $(this);
                mdrawer.swtichView();
            });
        },
    
        createLoader: function () {
            var svgHtml = '<svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg>';
            this.loader = document.createElement('div');
            this.loader.className = 'dt-loader';
            this.loader.innerHTML = svgHtml;
            this.container.find('.dt-action-content').prepend(this.loader);
        },
        toggleLoader: function (display) {
            if (this.loader) {
                if (display) {
                    if (display == 'show') {
                        $(this.loader).addClass('active');
                    } else {
                        $(this.loader).removeClass('active');
                    }
                } else {
                    $(this.loader).toggleClass('active');
                }
            }
        }
    };
    
    //The following is used to toggle the menu
    
    /*
     * Active customizer layout option
     */
    function activeLayoutHandle(layout) {
        var $layoutContainer = jQuery('#sidebar-layout');
        $layoutContainer.find('.choose-option').removeClass('active');
    
        if (layout === 'folded') {
            $layoutContainer.find('[data-value=folded]').parent().addClass('active');
        } else if (layout === 'drawer') {
            $layoutContainer.find('[data-value=drawer]').parent().addClass('active');
        } else if (layout === 'default') {
            $layoutContainer.find('[data-value=default]').parent().addClass('active');
        }
    }
    
    /*
     * Active fixed style
     */
    function activeFixedStyle() {
        var $body = jQuery('body');
        var $toggleFixedHeader = jQuery('#toggle-fixed-header');
        var $toggleFixedSidebar = jQuery('#toggle-fixed-sidebar');
    
        if ($body.hasClass('dt-header--fixed')) {
            $toggleFixedHeader.parent().addClass('active');
        } else {
            $toggleFixedHeader.parent().removeClass('active');
        }
    
        if ($body.hasClass('dt-sidebar--fixed')) {
            $toggleFixedSidebar.parent().addClass('active');
        } else {
            $toggleFixedSidebar.parent().removeClass('active');
        }
    }

    // init Drawer
    axis96.init();
    dtDrawer.init();

    activeFixedStyle();

    $('.dt-brand__tool').on('click', function () {
        if (axis96.sidebar.drawerRef.hasClass('dt-drawer')) {
            axis96.sidebar.toggle();
        }

        $(this).toggleClass('active');
    });

    /* toggle-button */
    var $toggleBtn = $('.toggle-button');
    if ($toggleBtn.length > 0) {
        $toggleBtn.on('click', function (event) {
            event.preventDefault();
            event.stopPropagation();

            $(this).toggleClass('active');
        });
    }

    /* Sidebar */
    var $sidebar = $('.dt-sidebar');

    $sidebar.hover(function () {
        if ($body.hasClass('dt-sidebar--folded')) {
            $body.addClass('dt-sidebar--expended');
        }
    }, function () {
        if ($body.hasClass('dt-sidebar--folded')) {
            $body.removeClass('dt-sidebar--expended');
        }
    });
    /* /Sidebar */

    /*Popover*/
    $('[data-toggle="popover"]').popover();

    /*Tooltip*/
    $('[data-toggle="tooltip"]').tooltip();

    /*Scroll Spy*/
    $('.scrollspy-horizontal').scrollspy({target: '#scrollspy-horizontal'});

    $('.scrollspy-vertical').scrollspy({target: '#scrollspy-vertical'});

    $('.scrollspy-list-group').scrollspy({target: '#scrollspy-list-group'});

    // Displaying user info card on contact hover
    var $mailContacts = $('.contacts-list .dt-contact');
    var $userInfoCard = $('.user-info-card');

    $userInfoCard.hover(function () {
        $userInfoCard.addClass('active').show();
    }, function () {
        $userInfoCard.hide().removeClass('active');
    });

    $mailContacts.each(function (index) {
        var $contact = $(this);

        $contact.hover(function (event) {
            var contactWidth = $contact.outerWidth(true);
            var positionValue = $contact.offset();
            var bodyHeight = $body.outerHeight(true);
            var bodyWidth = $body.outerWidth(true);

            if (bodyWidth > 767) {
                var userPic = $('.dt-avatar', $contact).attr('src');
                var userName = $('.dt-contact__title', $contact).text();

                if (userPic) {
                    $('.profile-placeholder', $userInfoCard).hide();
                    $('.profile-pic', $userInfoCard).attr('src', userPic).show();
                } else {
                    $('.profile-pic', $userInfoCard).hide();
                    $('.profile-placeholder', $userInfoCard).text(userName.substring(0, 2)).show();
                }

                $('.dt-avatar-name', $userInfoCard).text(userName);

                var infoCardHeight = $userInfoCard.outerHeight(true);
                var offsetTop = positionValue.top;
                if (bodyHeight < (positionValue.top + infoCardHeight + 20)) {
                    offsetTop = (bodyHeight - infoCardHeight - 20)
                }

                $userInfoCard.css({
                    top: offsetTop,
                    left: (positionValue.left + contactWidth - 15)
                }).show();
            }
        }, function (event) {
            if (!$userInfoCard.hasClass('active')) {
                $userInfoCard.hide();
            }
        });
    });

})(jQuery);