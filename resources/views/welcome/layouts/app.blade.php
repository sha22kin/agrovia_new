<!DOCTYPE html>
<html lang="en-US">
    <head>
        
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <!-- Required meta tags -->
        <meta charset="utf-8" />
        <meta http-equiv="x-ua-compatible" content="ie=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{csrf_token()}}" />
        @yield('title')
        <!-- Favicon -->
        <link rel="shortcut icon" type="image/x-icon" href="{{asset(general()->favicon())}}" />
        @yield('SEO')
        
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Josefin+Sans:ital,wght@0,100..700;1,100..700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

        <!-- Bootstrap CS CDN -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous" />
        <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css" />

        <!-- Font Awesome CSS CDN-->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        
        <link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/themes/smoothness/jquery-ui.css" />

        <!-- Slick Slider CSS CDN-->
        <link rel="stylesheet" type="text/css" href="{{asset('public/welcome/css/slick.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('public/welcome/css/slick-theme.css')}}" />
        <link rel="stylesheet" type="text/css" href="{{asset('public/welcome/css/jquery.fancybox.css')}}" />
        
        <!-- Start WOWSlider.com HEAD section -->
        <link rel="stylesheet" type="text/css" href="{{asset('public/welcome/engine1/style.css')}}" />
        <script type="text/javascript" src="{{asset('public/welcome/engine1/jquery.js')}}"></script>
        <!-- End WOWSlider.com HEAD section -->

        <!-- Matis Menus CSS -->
        <link rel="stylesheet" href="{{asset('public/welcome/css/metisMenu.css')}}" />
        
        <!-- Custom Css for this Design -->
        <link rel="stylesheet" href="{{assetV('public/welcome/css/style.css')}}" />
        <link rel="stylesheet" href="{{assetV('public/welcome/css/newStyle.css')}}" />
        <link rel="stylesheet" href="{{assetV('public/welcome/css/pageStyle.css')}}" />
        <link rel="stylesheet" href="{{assetV('public/welcome/css/adCard.css')}}" />
        
        
        
        
        <style>
            .looping{
                position:relative;
            }
            .looping::before {
                position: absolute;
                content: '';
                display: block;
                width: 25px;
                height: 25px;
                border: 3px solid #cf2525;
                border-top-color: transparent; /* Creates a spinning effect */
                border-radius: 50%;
                animation: spin 1s linear infinite;
            }
            
            /* Animation for spinning */
            @keyframes spin {
                0% { transform: rotate(0deg); }
                100% { transform: rotate(360deg); }
            }
            
            
            
            
            
            
            
            
            
            .lang {
    cursor: pointer;
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
}

.lang:hover {
    opacity: 0.7;
}

/* Hide Google Translate UI */
.goog-te-banner-frame.skiptranslate,
.goog-te-gadget-icon {
    display: none !important;
}

body {
    top: 0 !important;
}

.goog-te-gadget {
    font-size: 0 !important;
}

.goog-te-gadget .goog-te-combo {
    display: none !important;
}
            
            
            
            
            
            
            /* =========================
   GOOGLE TRANSLATE HIDE
========================= */

/* Hide Google Translate top notification bar */
.goog-te-banner-frame,
iframe.goog-te-banner-frame {
    display: none !important;
    visibility: hidden !important;
    height: 0 !important;
}

/* Google sometimes adds this class to body */
body {
    top: 0 !important;
}

/* Hide Google Translate original UI */
.goog-te-gadget {
    font-size: 0 !important;
}

/* Hide Google Translate select */
.goog-te-combo {
    display: none !important;
}

/* Hide Google Translate container */
#google_translate_element {
    display: none !important;
}

/* Hide Google Translate tooltip */
.goog-tooltip,
.goog-tooltip:hover {
    display: none !important;
}

/* Remove translated element outline */
.goog-text-highlight {
    background: none !important;
    box-shadow: none !important;
}

/* =========================
   LANGUAGE BUTTON
========================= */

.lang {
    cursor: pointer;
    user-select: none;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 12px;
    font-size: 14px;
    font-weight: 500;
    transition: 0.3s;
        border: 1px solid #ededed;
    border-radius: 5px;
}

.lang:hover {
    opacity: 0.7;
}
            
            
.VIpgJd-ZVi9od-ORHb-OEVmcd {

    display: none !important;
}
            
.VIpgJd-yAWNEb-L7lbkb {
    display: none !important;
}
          .top-header .row {
    gap: 0;
}  

.btn-sell:hover {
    color: #fff;
}







/* Header Styling */
    .mobile-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 15px 20px;
      background: #ffffff;
      box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
      position: sticky;
      top: 0;
      z-index: 100;
    }

    .logo {
      font-size: 20px;
      font-weight: 700;
      color: #6366f1;
      text-decoration: none;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .menu-btn {
      font-size: 22px;
      color: #1e293b;
      cursor: pointer;
    }

    /* Dark Overlay Background */
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(15, 23, 42, 0.5);
      backdrop-filter: blur(2px);
      z-index: 998;
      opacity: 0;
      visibility: hidden;
      transition: all 0.3s ease-in-out;
    }

    .overlay.active {
      opacity: 1;
      visibility: visible;
    }

    /* Sidebar Drawer (Right to Left Setup) */
    .sidebar {
      position: fixed;
      top: 0;
      right: -300px; /* Hidden outside the right edge */
      width: 280px;
      height: 100%;
      background: #ffffff;
      z-index: 999;
      box-shadow: -4px 0 20px rgba(0, 0, 0, 0.1);
      transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
      display: flex;
      flex-direction: column;
    }

    /* Slide Right to Left Transition */
    .sidebar.active {
      transform: translateX(-300px); 
    }

    .sidebar-header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 18px 20px;
      border-bottom: 1px solid #f1f5f9;
    }

    .close-btn {
      font-size: 20px;
      color: #64748b;
      cursor: pointer;
      transition: color 0.2s;
    }

    .close-btn:hover {
      color: #ef4444;
    }

    /* Navigation List */
    .sidebar-menu {
      list-style: none;
      padding: 10px 0;
      overflow-y: auto;
    }

    .sidebar-menu a {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 14px 20px;
      color: #334155;
      text-decoration: none;
      font-weight: 500;
      font-size: 15px;
      transition: background 0.2s, color 0.2s;
    }

    .sidebar-menu a:hover {
      background: #f8fafc;
      color: #6366f1;
    }

    .toggle-icon {
      font-size: 13px;
      color: #94a3b8;
    }

    /* Submenu styling */
    .submenu {
      list-style: none;
      background: #f8fafc;
      display: none; 
    }

    .submenu a {
      padding-left: 42px;
      font-size: 14px;
      color: #64748b;
    }

    .submenu a:hover {
      color: #6366f1;
    }

.mobile-header a img {
    max-width: 220px;
}

/* The drawer menu is only for phones and tablets; desktop uses the header menu. */
@media (min-width: 992px) {
    #sidebar, #overlay { display: none !important; }
}







        </style>
        @stack('css')
        
    </head>
    
    <body>
        
        <div class="loading"></div>
        
        <!--<div id="pageLoader">-->
        <!--  <div class="loader-container">-->
        <!--    <svg width="100" height="100" viewBox="0 0 100 100">-->
        
        <!--      <circle class="circle outer" cx="50" cy="50" r="45" />-->
              
             
        <!--      <circle class="circle inner" cx="50" cy="50" r="25" />-->
              
          
        <!--      <circle class="dots" cx="50" cy="5" r="3" />-->
        <!--      <circle class="dots" cx="95" cy="50" r="3" />-->
        <!--      <circle class="dots" cx="50" cy="95" r="3" />-->
        <!--      <circle class="dots" cx="5" cy="50" r="3" />-->
        <!--    </svg>-->
        <!--  </div>-->
        <!--</div>-->
        
        <!--Header Part Include Start-->
        @include(welcomeTheme().'layouts.header')
        @if(!Auth::check())
        @include(welcomeTheme().'layouts.authModal')
        @endif
        <!--Main Content Section Start-->
        @yield('contents')
        <!--Main Content Section End-->
        
        <!--Footer Part Include Start-->
        @include(welcomeTheme().'layouts.footer')
        
        <!-- Jquery Script  CDN-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        
        <!-- Bootstrap Script  CDN-->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

        <!-- Metis Menus Script -->
        <script src="{{asset('public/welcome/js/metisMenu.min.js')}}"></script>

        <!-- Slick slider CDN -->
        <script type="text/javascript" src="{{asset('public/welcome/js/slick.min.js')}}"></script>
        
        <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.2/jquery-ui.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery-zoom/1.7.21/jquery.zoom.js"></script>

        <!--iconify Script CDN-->
        <script src="https://code.iconify.design/1/1.0.7/iconify.min.js"></script>

        <!-- Custom Script for this Design -->
        <script src="{{assetV('public/welcome/js/myScript.js')}}"></script>
        
        <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
        <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
        
        
<script>
    $(document).ready(function() {
      // Sidebar Open Function (Right to Left Slide)
      $('#openMenu').on('click', function() {
        $('#sidebar').addClass('active');
        $('#overlay').addClass('active');
      });

      // Function to close Sidebar
      function closeSidebar() {
        $('#sidebar').removeClass('active');
        $('#overlay').removeClass('active');
      }

      // Outside Overlay click and Cross button click to close
      $('#closeMenu, #overlay').on('click', function() {
        closeSidebar();
      });

      // Turning a tablet or widening the window to desktop size closes the drawer.
      $(window).on('resize', function() {
        if (window.innerWidth >= 992) {
          closeSidebar();
        }
      });

      // Submenu Toggle (+ / - functionality)
      $('.has-submenu > a').on('click', function(e) {
        e.preventDefault();
        
        var $submenu = $(this).next('.submenu');
        var $icon = $(this).find('.toggle-icon');

        // Toggle Submenu with animation
        $submenu.slideToggle(250);

        // Dynamic Plus / Minus Change
        if ($icon.hasClass('fa-plus')) {
          $icon.removeClass('fa-plus').addClass('fa-minus');
        } else {
          $icon.removeClass('fa-minus').addClass('fa-plus');
        }

        // Accordion functionality: Close other submenus
        $('.submenu').not($submenu).slideUp(250);
        $('.has-submenu > a .toggle-icon').not($icon).removeClass('fa-minus').addClass('fa-plus');
      });
    });
  </script>
        
        
<script>
    let googleTranslateReady = false;

    /*
    |--------------------------------------------------------------------------
    | Google Translate Initialize
    |--------------------------------------------------------------------------
    */
    function googleTranslateElementInit() {

        new google.translate.TranslateElement({
            pageLanguage: 'en',
            includedLanguages: 'en,bn',
            autoDisplay: false
        }, 'google_translate_element');

        googleTranslateReady = true;
    }


    /*
    |--------------------------------------------------------------------------
    | Get Google Translate Select
    |--------------------------------------------------------------------------
    */
    function getTranslateSelect() {
        return document.querySelector('.goog-te-combo');
    }


    /*
    |--------------------------------------------------------------------------
    | Change Language
    |--------------------------------------------------------------------------
    */
    function changeLanguage(language) {

        const select = getTranslateSelect();

        if (!select) {
            setTimeout(function () {
                changeLanguage(language);
            }, 300);

            return;
        }

        select.value = language;

        select.dispatchEvent(new Event('change'));

        updateLanguageButton(language);
    }


    /*
    |--------------------------------------------------------------------------
    | Update Button Text
    |--------------------------------------------------------------------------
    */
    function updateLanguageButton(language) {

        const text = document.getElementById('languageText');

        if (!text) {
            return;
        }

        if (language === 'bn') {
            text.textContent = 'বাংলা';
        } else {
            text.textContent = 'English';
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Language Toggle
    |--------------------------------------------------------------------------
    */
    document.addEventListener('DOMContentLoaded', function () {

        const languageToggle = document.getElementById('languageToggle');

        if (!languageToggle) {
            return;
        }

        languageToggle.addEventListener('click', function () {

            let currentLanguage =
                localStorage.getItem('siteLanguage') || 'en';

            let newLanguage =
                currentLanguage === 'en' ? 'bn' : 'en';

            localStorage.setItem('siteLanguage', newLanguage);

            changeLanguage(newLanguage);
        });


        /*
        |--------------------------------------------------------------------------
        | Restore Saved Language
        |--------------------------------------------------------------------------
        */
        const savedLanguage =
            localStorage.getItem('siteLanguage') || 'en';

        updateLanguageButton(savedLanguage);

        /*
        |--------------------------------------------------------------------------
        | Wait for Google Translate
        |--------------------------------------------------------------------------
        */
        const translateChecker = setInterval(function () {

            const select = getTranslateSelect();

            if (select) {

                clearInterval(translateChecker);

                /*
                | Default = English
                */
                if (savedLanguage === 'en') {
                    select.value = 'en';
                    select.dispatchEvent(new Event('change'));
                }

                /*
                | Restore Bengali
                */
                if (savedLanguage === 'bn') {
                    setTimeout(function () {
                        changeLanguage('bn');
                    }, 500);
                }
            }

        }, 300);

    });


    /*
    |--------------------------------------------------------------------------
    | Hide Google Translate Banner Again
    |--------------------------------------------------------------------------
    */
    function hideGoogleTranslateBar() {

        const elements = document.querySelectorAll(
            '.goog-te-banner-frame, iframe.goog-te-banner-frame'
        );

        elements.forEach(function (element) {
            element.style.display = 'none';
            element.style.visibility = 'hidden';
            element.style.height = '0';
        });

        document.body.style.top = '0px';
    }


    /*
    |--------------------------------------------------------------------------
    | Continuously Hide Google Bar
    |--------------------------------------------------------------------------
    */
    setInterval(function () {
        hideGoogleTranslateBar();
    }, 500);

</script>

<script
    src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit">
</script>
        
        
        
        
        
        
        
        
        <script>
            $(document).ready(function() {
                // $("#pageLoader").hide(); // Ensure the object is hidden initially
                $("#pageLoader").fadeOut(2000); // Fades in over 2 seconds
                
                $('.registerBtn').click(function(){
                    $('.loginModal').hide();
                    $('.RegisterModal').show();
                    $('.registerAuth input').val('');
                });
                
                $('.loginBtn').click(function(){
                    $('.loginModal').show();
                    $('.RegisterModal').hide();
                });
                
                $("#district").on("change", function(){
                    var id = $(this).val();
                      if(id==''){
                       $('#thana').empty().append('<option value="">Select thana</option>');
                      }
                      var url ='{{url('geo/filter')}}' + '/'+id;
                      $.get(url,function(data){
                        $('#thana').empty().append(data.geoData);  
                      });   
                });
                
                
                $(".loginAuth").submit(function(e) {
                    e.preventDefault(); // Prevent default form submission
                    $(".loginAuth button").addClass('looping');
                    $(".loginAuth button").attr('disabled',true);
                    $(".msgBoxLogin").html(""); // Clear previous messages
                    let formData = $(this).serialize(); // Serialize form data
        
                    $.ajax({
                        url: "{{ route('login') }}", // Define this route in web.php
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
                        },
                        success: function(response) {
                            if (response.status === "success") {
                                window.location.href = response.redirect; // Redirect to dashboard
                            } else if (response.status === "error") {
                                $(".msgBoxLogin").html('<span style="color: #ffc107;">' + response.message + '</span>');
                            } else if (response.status === "errors") {
                                let errorHtml = "<ul >";
                                $.each(response.datas, function(key, value) {
                                    errorHtml += "<li style='color: #ffc107;'>" + value[0] + "</li>"; // Display each error
                                });
                                errorHtml += "</ul>";
                                $(".msgBoxRegister").html(errorHtml); //
                            }
                            $(".loginAuth button").removeClass('looping');
                            $(".loginAuth button").attr('disabled',false);
                        },
                        error: function(xhr) {
                            alert('error');
                            $(".loginAuth button").removeClass('looping');
                            $(".loginAuth button").attr('disabled',false);
                        }
                    });
                });
                
                $(".registerAuth").submit(function(e) {
                    e.preventDefault(); // Prevent default form submission
                    $(".registerAuth button").addClass('looping');
                    $(".registerAuth button").attr('disabled',true);
                    $(".msgBoxRegister").html(""); // Clear previous messages
                    let formData = $(this).serialize(); // Serialize form data
        
                    $.ajax({
                        url: "{{ route('register') }}", // Define this route in web.php
                        type: "POST",
                        data: formData,
                        dataType: "json",
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // CSRF Token
                        },
                        success: function(response) {
                            
                            if (response.status === "success" || response.status === "verify") {
                                window.location.href = response.redirect; // Redirect to dashboard, or to the email OTP screen
                            } else if (response.status === "error") {
                                $(".msgBoxRegister").html('<span style="color: #ffc107;">' + response.message + '</span>');
                            } else if (response.status === "errors") {
                                let errorHtml = "<ul >";
                                $.each(response.datas, function(key, value) {
                                    errorHtml += "<li style='color: #ffc107;'>" + value[0] + "</li>"; // Display each error
                                });
                                errorHtml += "</ul>";
                                $(".msgBoxRegister").html(errorHtml); //
                            }
                            $(".registerAuth button").removeClass('looping');
                            $(".registerAuth button").attr('disabled',false);
                        },
                        error: function(xhr) {
                            alert('error');
                            $(".registerAuth button").removeClass('looping');
                            $(".registerAuth button").attr('disabled',false);
                        }
                    });
                });
                
            });
        </script>
        
        <script>
            var max = 100;
            var maxHeight = $(document).height();
            var width = 0;
            var offset = 0;
            $(document).scroll(function(){
              offset = Math.floor($(window).scrollTop() / ($(document).height() - $(window).height())* 100) ;
              $('.loading').css('width',offset + '%');
            });
        </script>
        
        <script>
            (function () {
            	$('.hamburger-menu').on('click', function() {
            		$('.bar').toggleClass('animate');
                $('.mobile-menu').toggleClass('active');
                return false;
            	})
              $('.has-children').on ('click', function() {
            		   $(this).children('ul').slideToggle('slow', 'swing');
                   $('.icon-arrow').toggleClass('open');
            	});
            })();
        </script>
        
        <script>
            AOS.init();
        </script>

        <script type="text/javascript">
            $(".freeSlick").slick({
                autoplay: true,
                autoplaySpeed: 3000,
                dots: false,
                infinite: true,
                speed: 300,
                prevArrow: '<i class="fa fa-angle-left"></i>',
                nextArrow: '<i class="fa fa-angle-right"></i>',
                slidesToShow: 5,
                slidesToScroll: 1,
                responsive: [
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: 3,
                            slidesToScroll: 1,
                            infinite: true,
                            dots: true,
                        },
                    },
                    {
                        breakpoint: 600,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    {
                        breakpoint: 480,
                        settings: {
                            slidesToShow: 2,
                            slidesToScroll: 1,
                        },
                    },
                    // You can unslick at a given breakpoint now by adding:
                    // settings: "unslick"
                    // instead of a settings object
                ],
            });
        </script>
        
        <script type="text/javascript">
            $('.slider-banner').slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: true,
              prevArrow: '<i class="fa fa-angle-left"></i>',
                nextArrow: '<i class="fa fa-angle-right"></i>',
              fade: true,
              asNavFor: '.slider-bannerads'
            });
            $('.slider-bannerads').slick({
              slidesToShow: 4,
              slidesToScroll: 1,
              asNavFor: '.slider-banner',
              dots: false,
              centerMode: true,
              focusOnSelect: true
            });
        </script>
        
        <script type="text/javascript">
            $('.slider-boost').slick({
              slidesToShow: 1,
              slidesToScroll: 1,
              arrows: true,
              prevArrow: '<i class="fa fa-angle-left"></i>',
                nextArrow: '<i class="fa fa-angle-right"></i>',
              fade: true,
              asNavFor: '.slider-boostad'
            });
            $('.slider-boostad').slick({
              slidesToShow: 5,
              slidesToScroll: 1,
              asNavFor: '.slider-boost',
              dots: false,
              centerMode: true,
              focusOnSelect: true
            });
        </script>

        <script>
            function openSearch() {
              document.getElementById("myOverlay").style.display = "block";
            }

            function closeSearch() {
              document.getElementById("myOverlay").style.display = "none";
            }
        </script>
        
        @stack('js')
        
    </body>
</html>
