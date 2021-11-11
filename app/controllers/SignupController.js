
/* Setup Singup page controller */
ClubhouseNowApp.controller('SignupController', ['$rootScope', '$scope', '$http', '$state',  '$modal', '$modalStack', 'settings', function($rootScope, $scope, $http, $state, $modal, $modalStack, settings) { 
    $scope.$on('$viewContentLoaded', function() {   
    	// initialize core components
    	Metronic.initAjax();
        
    });
    $scope.initCreateAccount = function(){
        FormWizard.init(); 
        //angular.element('body').addClass('page-md login registration');
        $rootScope.class = "page-md login registration";
        $scope.no_of_emp = {
            availableOptions: $rootScope.no_of_empOptions,
            selectedOption: {id: '1-20 employees'}
        };
    }
    
    $scope.createAccount = function(){
        $scope.users.no_of_emps = $scope.no_of_emp.selectedOption.id;
        $http({
               url: 'api/module/users/createAccount/',
               method: "POST",
               params: $scope.users
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.error = false; 
                   //$(".show-success").text('');
                   //$(".show-success").text(resdata.error_code);
                   $state.go("thankyou");
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
    }
    
}]);
