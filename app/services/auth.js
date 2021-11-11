'use strict';

angular.module('ClubhouseNowApp')
        .factory('Auth', ['$http', '$rootScope', '$window', 'Session', 'AUTH_EVENTS', '$timeout','$state',
     function($http, $rootScope, $window, Session, AUTH_EVENTS,$timeout,$state) {
          var authService = {};
          if($window.sessionStorage["userInfo"] != null){
                 var data = JSON.parse($window.sessionStorage["userInfo"]);
                 Session.create(data);       
          } 
          
          //check if the user is authenticated
          authService.isAuthenticated = function() {
                    return !!Session.user;
          };

          //check if the user is authorized to access the next route
          //this function can be also used on element level
          authService.isAuthorized = function(authorizedRoles) {
               if (!angular.isArray(authorizedRoles)) {
                    authorizedRoles = [authorizedRoles];
               }
               return (authService.isAuthenticated() && authorizedRoles.indexOf(Session.userRole) !== -1);
          };

          //log out the user and broadcast the logoutSuccess event
          authService.logout = function() { 
               //var userRole = Session.userRole;
               Session.destroy();
               $window.sessionStorage.removeItem("userInfo");
               $http({
                        url: 'api/module/common/logout',
                        method: "POST",
               })
               $rootScope.$broadcast(AUTH_EVENTS.logoutSuccess);
               $rootScope.islogin = false; 
               /*if(userRole == 'SPEadmin'){
                   $state.go('events');
               } else {
                    $state.go('dashboard');
               }*/
               $state.go('dashboard');
               window.location.reload();
          }

          return authService;
     }]);