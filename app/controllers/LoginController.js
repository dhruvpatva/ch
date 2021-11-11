'use strict';

angular.module('ClubhouseNowApp')
.controller('LoginCtrl', [ '$scope','$rootScope' ,'$state', '$modalInstance' , '$window', 'Auth', '$http','Session', 'AUTH_EVENTS', 
function($scope, $rootScope, $state, $modalInstance, $window, Auth, $http, Session, AUTH_EVENTS) {
	$scope.credentials = {};
	$scope.loginForm = {};
	$scope.error = false;
	
	//when the form is submitted
	$scope.submit = function(data) {
		$scope.submitted = true;
		if (!$scope.loginForm.$invalid) {
			$scope.login($scope.credentials); 
		} else { 
			$scope.error = true;
                        return;
		}
	};

	//Performs the login function, by sending a request to the server with the Auth service
	$scope.login = function(credentials) {
		$scope.error = false;
                $http({
                        url: 'api/module/users/login',
                        method: "POST",
                        data: {'user': credentials}
                })
                .then(function(response) {
                        // success
                        var loginData = response.data.data;
                        if (response.data.success == 1) {
                             $window.sessionStorage["userInfo"] = JSON.stringify(loginData);
                             Session.create(loginData);
                             $rootScope.currentUser = loginData;
                             $rootScope.User = loginData;
                             $rootScope.$broadcast(AUTH_EVENTS.loginSuccess);
                             $modalInstance.close(); 
                             $state.go('dashboard');
                             window.location.reload();
                        } else { 
                             $scope.error = true; 
                             $scope.error_code = response.data.error_code;
                             console.log("error");
                             $rootScope.$broadcast(AUTH_EVENTS.loginFailed);
                        }
                },
                function(response) { // optional

                });
	};
	
	// if a session exists for current user (page was refreshed)
	// log him in again
	if ($window.sessionStorage["userInfo"]) {
               var credentials = JSON.parse($window.sessionStorage["userInfo"]);
               $scope.login(credentials);
	}
} ]);
