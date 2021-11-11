'use strict';

ClubhouseNowApp.controller('DashboardController', function($scope, $http, $timeout) {
    $scope.$on('$viewContentLoaded', function() {   
        // initialize core components
        Metronic.initAjax();
    });
    
    
   
    $scope.getDashboardDetails = function() {
          $http.get('api/module/dashboard/getDashboardDetails').success(function(data) { 
               $scope.total_enduser = data.total_enduser;
               $scope.total_speadmin = data.total_speadmin;
               $scope.total_spiuser = data.total_spiuser;
               $scope.total_events = data.total_events;
               $scope.total_classes = data.total_classes;
               $scope.$broadcast('dataloaded');
          });
     }
     
     $scope.$on('dataloaded', function() {
          $timeout(function() {
                Metronic.initAjax();
                
                // Handles counter up
                $().counterUp && $("[data-counter='counterup']").counterUp({
                    delay: 10,
                    time: 1e3
                });
                
          }, 0, false);
     })
     
     
});