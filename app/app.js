/***
 Metronic AngularJS App Main Script
 ***/
/* Metronic App */
var ClubhouseNowApp = angular.module("ClubhouseNowApp", [
     "ui.router",
     "ui.bootstrap",
     "oc.lazyLoad",
     "ngSanitize",
     "ngFileUpload",
     "angularUtils.directives.dirPagination"
])
        /*Constants regarding user login defined here*/
.constant('USER_ROLES', {
     all: '*',
     admin: 'admin',
     guest: 'guest',
     enduser: 'EndUser'
}).constant('AUTH_EVENTS', {
     loginSuccess: 'auth-login-success',
     loginFailed: 'auth-login-failed',
     logoutSuccess: 'auth-logout-success',
     sessionTimeout: 'auth-session-timeout',
     notAuthenticated: 'auth-not-authenticated',
     notAuthorized: 'auth-not-authorized'
}).config(function($httpProvider) {
     $httpProvider.interceptors.push([
          '$injector',
          function($injector) {
               return $injector.get('AuthInterceptor');
          }
     ]);
})



/* Configure ocLazyLoader(refer: https://github.com/ocombe/ocLazyLoad) */
ClubhouseNowApp.config(['$ocLazyLoadProvider', function($ocLazyLoadProvider) {
          $ocLazyLoadProvider.config({
               cssFilesInsertBefore: 'ng_load_plugins_before' // load the above css files before a LINK element with this ID. Dynamic CSS files must be loaded between core and theme css files
          });
     }]);


//AngularJS v1.3.x workaround for old style controller declarition in HTML
ClubhouseNowApp.config(['$controllerProvider', function($controllerProvider) {
          // this option might be handy for migrating old apps, but please don't use it
          // in new ones!
          $controllerProvider.allowGlobals();
     }]);

/********************************************
 END: BREAKING CHANGE in AngularJS v1.3.x:
 *********************************************/

/* Setup global settings */
ClubhouseNowApp.factory('settings', ['$rootScope', function($rootScope) {
          // supported languages
          var settings = {
               layout: {
                    pageSidebarClosed: false, // sidebar state
                    pageAutoScrollOnLoad: 1000 // auto scroll to top on page load
               },
               layoutImgPath: Metronic.getAssetsPath() + 'admin/layout/img/',
               layoutCssPath: Metronic.getAssetsPath() + 'admin/layout/css/'
          };

          $rootScope.settings = settings;

          return settings;
     }]);

/* Setup App Main Controller */
ClubhouseNowApp.controller('AppController', ['$scope', '$rootScope', '$timeout', '$modal', 'Auth', 'AUTH_EVENTS', 'USER_ROLES', function($scope, $rootScope, $timeout, $modal, Auth, AUTH_EVENTS, USER_ROLES) {
          $scope.$on('$viewContentLoaded', function() {

               //Layout.init(); //  Init entire layout(header, footer, sidebar, etc) on page load if the partials included in server side instead of loading with ng-include directive 
          });

          $scope.modalShown = false;
          var showLoginDialog = function() {
               if (!$scope.modalShown) {
                    $scope.modalShown = true;
                    $rootScope.class = "page-md login myloginpage";
                    var modalInstance = $modal.open({
                         templateUrl: 'tpl/login.html',
                         controller: "LoginCtrl",
                         backdrop: 'static',
                    });
                    modalInstance.result.then(function() {
                         $scope.modalShown = false;
                    });
               }
               $timeout(function() {
                    $(".page-spinner-bar").addClass('hide');
               });
               
          };
          
          
          var setCurrentUser = function() {
               $scope.currentUser = $rootScope.currentUser;
               $rootScope.User = $rootScope.currentUser;
          }

          var showNotAuthorized = function() {
               //alert('Not Authorized!');
               //window.location.href = "http://localhost/fittfinder/";
               swal({
                    title: "",
                    text: "Oops... Not Authorized!",
                    type: "error",
                    allowEscapeKey: false,
                    showCloseButton: true
                  },
                  function(){
                        window.location.href = "http://localhost/ClubhouseNow";
                  }); 
          }

          $scope.currentUser = null;
          $scope.userRoles = USER_ROLES;
          $scope.isAuthorized = Auth.isAuthorized;
          //listen to events of unsuccessful logins, to run the login dialog
          $rootScope.$on(AUTH_EVENTS.notAuthorized, showNotAuthorized);
          $rootScope.$on(AUTH_EVENTS.notAuthenticated, showLoginDialog);
          $rootScope.$on(AUTH_EVENTS.sessionTimeout, showLoginDialog);
          $rootScope.$on(AUTH_EVENTS.logoutSuccess, showLoginDialog);
          $rootScope.$on(AUTH_EVENTS.loginSuccess, setCurrentUser);
     }]);

/***
 Layout Partials.
 By default the partials are loaded through AngularJS ng-include directive. In case they loaded in server side(e.g: PHP include function) then below partial 
 initialization can be disabled and Layout.init() should be called on page load complete as explained above.
 ***/

/* Setup Layout Part - Header */
ClubhouseNowApp.controller('HeaderController', ['$scope', function($scope) {
          $scope.$on('$includeContentLoaded', function() {
               Layout.initHeader(); // init header
          });
     }]);

/* Setup Layout Part - Sidebar */
ClubhouseNowApp.controller('SidebarController', ['$scope', function($scope) {
          $scope.$on('$includeContentLoaded', function() {
               Layout.initSidebar(); // init sidebar
          });
     }]);

/* Setup Layout Part - Sidebar */
ClubhouseNowApp.controller('PageHeadController', ['$scope', function($scope) {
          $scope.$on('$includeContentLoaded', function() {
               Demo.init(); // init theme panel
          });
     }]);

/* Setup Layout Part - Footer */
ClubhouseNowApp.controller('FooterController', ['$scope', function($scope) {
          $scope.$on('$includeContentLoaded', function() {
               Layout.initFooter(); // init footer

          });
     }]);

/* Init global settings and run the app */
ClubhouseNowApp.run(["$rootScope", "$modal", "settings", "$state", 'Auth', 'AUTH_EVENTS', 'Session', '$http', '$window', function($rootScope, $modal, settings, $state, Auth, AUTH_EVENTS, Session, $http, $window) {
          $rootScope.$state = $state; // state to be accessed from view
          
          $rootScope.$on('$stateChangeStart', function(event, next) { 
               $rootScope.User = Session.user;
               var authorizedRolesList = next.data.authorizedRoles; 
               $rootScope.signup = false;
               //$('.modal').show();
               //angular.element('body').removeClass('page-md login registration');
               //$scope.class = "page-md login registration";
               if (jQuery.inArray(Session.userRole,authorizedRolesList) !== -1 || Session.userRole === undefined || Session.userRole === null){  
                    if (Session.user === undefined) { 
                         $http({
                              url: 'api/module/common/check',
                              method: "POST",
                         }).then(function(response) {
                              var loginData = response.data.data; 
                              if (response.data.success === 1) {
                                   $window.sessionStorage["userInfo"] = JSON.stringify(loginData);
                                   Session.create(loginData);
                                   $rootScope.currentUser = loginData;
                                   $rootScope.User = loginData;
                                   $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                              } else {
                                   var authorizedRoles = next.data.authorizedRoles; 
                                   if (!Auth.isAuthorized(authorizedRoles)) {
                                        event.preventDefault();
                                        if (Auth.isAuthenticated()) { 
                                             // user is not allowed
                                             $rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
                                        } else { // console.log(next.data); //console.log($rootScope);
                                             if (next.data.pageSubTitle !== 'Account' && next.data.pageSubTitle !== 'Thank you'){
                                                $('.modal').show();
                                                $('.modal-backdrop').show();
                                                $rootScope.class = "page-md login myloginpage";
                                                event.preventDefault();
                                                // user is not logged in
                                                $rootScope.$broadcast(AUTH_EVENTS.notAuthenticated);
                                             } else {
                                                $rootScope.signup = true;
                                                $('.modal').hide();
                                                $('.modal-backdrop').hide();
                                             }
                                        }
                                   }
                              }
                         });
                    }
                } else { 
                        event.preventDefault();
                        // user is not allowed
                        $rootScope.$broadcast(AUTH_EVENTS.notAuthorized);
                }
               
          });
          $rootScope.$on('$stateChangeSuccess', function(event, next) {
               $rootScope.User = Session.user;
          });
          /* To show current active state on menu */
          $rootScope.getClass = function(path) {
               if ($state.current.name == path) {
                    return "active";
               } else {
                    return "";
               }
          }

          $rootScope.logout = function() {
               Auth.logout();

          };

          $rootScope.convertToDate = function(stringDate) {
               var dateOut = new Date(stringDate);
               return dateOut;
          };
          $rootScope.timezoneoptions = [{id:'US/Arizona',name:"(GMT-07:00) Arizona"},{id:'US/Mountain',name:"(GMT-07:00) Mountain Time (US &amp; Canada)"},{id:'America/Chihuahua',name:"(GMT-07:00) Chihuahua"},{id:'America/Mazatlan',name:"(GMT-07:00) Mazatlan"},{id:'America/Mexico_City',name:"(GMT-06:00) Mexico City"},{id:'America/Monterrey',name:"(GMT-06:00) Monterrey"},{id:'Canada/Saskatchewan',name:"(GMT-06:00) Saskatchewan"},{id:'US/Central',name:"(GMT-06:00) Central Time (US &amp; Canada)"},{id:'US/Eastern',name:"(GMT-05:00) Eastern Time (US &amp; Canada)"},{id:'US/East-Indiana',name:"(GMT-05:00) Indiana (East)"},{id:'America/Bogota',name:"(GMT-05:00) Bogota"},{id:'America/Lima',name:"(GMT-05:00) Lima"},{id:'America/Caracas',name:"(GMT-04:30) Caracas"},{id:'Canada/Atlantic',name:"(GMT-04:00) Atlantic Time (Canada)"},{id:'America/La_Paz',name:"(GMT-04:00) La Paz"},{id:'America/Santiago',name:"(GMT-04:00) Santiago"},{id:'Canada/Newfoundland',name:"(GMT-03:30) Newfoundland"},{id:'America/Buenos_Aires',name:"(GMT-03:00) Buenos Aires"},{id:'Greenland',name:"(GMT-03:00) Greenland"},{id:'Atlantic/Stanley',name:"(GMT-02:00) Stanley"},{id:'Atlantic/Azores',name:"(GMT-01:00) Azores"},{id:'Atlantic/Cape_Verde',name:"(GMT-01:00) Cape Verde Is."},{id:'Africa/Casablanca',name:"(GMT) Casablanca"},{id:'Europe/Dublin',name:"(GMT) Dublin"},{id:'Europe/Lisbon',name:"(GMT) Lisbon"},{id:'Europe/London',name:"(GMT) London"},{id:'Africa/Monrovia',name:"(GMT) Monrovia"},{id:'Europe/Amsterdam',name:"(GMT+01:00) Amsterdam"},{id:'Europe/Belgrade',name:"(GMT+01:00) Belgrade"},{id:'Europe/Berlin',name:"(GMT+01:00) Berlin"},{id:'Europe/Bratislava',name:"(GMT+01:00) Bratislava"},{id:'Europe/Brussels',name:"(GMT+01:00) Brussels"},{id:'Europe/Budapest',name:"(GMT+01:00) Budapest"},{id:'Europe/Copenhagen',name:"(GMT+01:00) Copenhagen"},{id:'Europe/Ljubljana',name:"(GMT+01:00) Ljubljana"},{id:'Europe/Madrid',name:"(GMT+01:00) Madrid"},{id:'Europe/Paris',name:"(GMT+01:00) Paris"},{id:'Europe/Prague',name:"(GMT+01:00) Prague"},{id:'Europe/Rome',name:"(GMT+01:00) Rome"},{id:'Europe/Sarajevo',name:"(GMT+01:00) Sarajevo"},{id:'Europe/Skopje',name:"(GMT+01:00) Skopje"},{id:'Europe/Stockholm',name:"(GMT+01:00) Stockholm"},{id:'Europe/Vienna',name:"(GMT+01:00) Vienna"},{id:'Europe/Warsaw',name:"(GMT+01:00) Warsaw"},{id:'Europe/Zagreb',name:"(GMT+01:00) Zagreb"},{id:'Europe/Athens',name:"(GMT+02:00) Athens"},{id:'Europe/Bucharest',name:"(GMT+02:00) Bucharest"},{id:'Africa/Cairo',name:"(GMT+02:00) Cairo"},{id:'Africa/Harare',name:"(GMT+02:00) Harare"},{id:'Europe/Helsinki',name:"(GMT+02:00) Helsinki"},{id:'Europe/Istanbul',name:"(GMT+02:00) Istanbul"},{id:'Asia/Jerusalem',name:"(GMT+02:00) Jerusalem"},{id:'Europe/Kiev',name:"(GMT+02:00) Kyiv"},{id:'Europe/Minsk',name:"(GMT+02:00) Minsk"},{id:'Europe/Riga',name:"(GMT+02:00) Riga"},{id:'Europe/Sofia',name:"(GMT+02:00) Sofia"},{id:'Europe/Tallinn',name:"(GMT+02:00) Tallinn"},{id:'Europe/Vilnius',name:"(GMT+02:00) Vilnius"},{id:'Asia/Baghdad',name:"(GMT+03:00) Baghdad"},{id:'Asia/Kuwait',name:"(GMT+03:00) Kuwait"},{id:'Africa/Nairobi',name:"(GMT+03:00) Nairobi"},{id:'Asia/Riyadh',name:"(GMT+03:00) Riyadh"},{id:'Europe/Moscow',name:"(GMT+03:00) Moscow"},{id:'Asia/Tehran',name:"(GMT+03:30) Tehran"},{id:'Asia/Baku',name:"(GMT+04:00) Baku"},{id:'Europe/Volgograd',name:"(GMT+04:00) Volgograd"},{id:'Asia/Muscat',name:"(GMT+04:00) Muscat"},{id:'Asia/Tbilisi',name:"(GMT+04:00) Tbilisi"},{id:'Asia/Yerevan',name:"(GMT+04:00) Yerevan"},{id:'Asia/Kabul',name:"(GMT+04:30) Kabul"},{id:'Asia/Karachi',name:"(GMT+05:00) Karachi"},{id:'Asia/Tashkent',name:"(GMT+05:00) Tashkent"},{id:'Asia/Kolkata',name:"(GMT+05:30) Kolkata"},{id:'Asia/Kathmandu',name:"(GMT+05:45) Kathmandu"},{id:'Asia/Yekaterinburg',name:"(GMT+06:00) Ekaterinburg"},{id:'Asia/Almaty',name:"(GMT+06:00) Almaty"},{id:'Asia/Dhaka',name:"(GMT+06:00) Dhaka"},{id:'Asia/Novosibirsk',name:"(GMT+07:00) Novosibirsk"},{id:'Asia/Bangkok',name:"(GMT+07:00) Bangkok"},{id:'Asia/Jakarta',name:"(GMT+07:00) Jakarta"},{id:'Asia/Krasnoyarsk',name:"(GMT+08:00) Krasnoyarsk"},{id:'Asia/Chongqing',name:"(GMT+08:00) Chongqing"},{id:'Asia/Hong_Kong',name:"(GMT+08:00) Hong Kong"},{id:'Asia/Kuala_Lumpur',name:"(GMT+08:00) Kuala Lumpur"},{id:'Australia/Perth',name:"(GMT+08:00) Perth"},{id:'Asia/Singapore',name:"(GMT+08:00) Singapore"},{id:'Asia/Taipei',name:"(GMT+08:00) Taipei"},{id:'Asia/Ulaanbaatar',name:"(GMT+08:00) Ulaan Bataar"},{id:'Asia/Urumqi',name:"(GMT+08:00) Urumqi"},{id:'Asia/Irkutsk',name:"(GMT+09:00) Irkutsk"},{id:'Asia/Seoul',name:"(GMT+09:00) Seoul"},{id:'Asia/Tokyo',name:"(GMT+09:00) Tokyo"},{id:'Australia/Adelaide',name:"(GMT+09:30) Adelaide"},{id:'Australia/Darwin',name:"(GMT+09:30) Darwin"},{id:'Asia/Yakutsk',name:"(GMT+10:00) Yakutsk"},{id:'Australia/Brisbane',name:"(GMT+10:00) Brisbane"},{id:'Australia/Canberra',name:"(GMT+10:00) Canberra"},{id:'Pacific/Guam',name:"(GMT+10:00) Guam"},{id:'Australia/Hobart',name:"(GMT+10:00) Hobart"},{id:'Australia/Melbourne',name:"(GMT+10:00) Melbourne"},{id:'Pacific/Port_Moresby',name:"(GMT+10:00) Port Moresby"},{id:'Australia/Sydney',name:"(GMT+10:00) Sydney"},{id:'Asia/Vladivostok',name:"(GMT+11:00) Vladivostok"},{id:'Asia/Magadan',name:"(GMT+12:00) Magadan"},{id:'Pacific/Auckland',name:"(GMT+12:00) Auckland"},{id:'Pacific/Fiji',name:"(GMT+12:00) Fiji"}];
          $rootScope.no_of_empOptions = [{"id":"1-20 employees","name":"1-20 employees"},{"id":"21-40 employees","name":"21-40 employees"},{"id":"41-60 employees","name":"41-60 employees"},{"id":"61-80 employees","name":"61-80 employees"},{"id":"81-100 employees","name":"81-100 employees"}];
          $rootScope.stateoptions = [{"id":"Select","name":"Select"},{"id":"Gujarat","name":"Gujarat"},{"id":"Maharashtra","name":"Maharashtra"},{"id":"Haryana","name":"Haryana"},{"id":"Jammu and Kashmir","name":"Jammu and Kashmir"},{"id":"Goa","name":"Goa"}];
      }]);
