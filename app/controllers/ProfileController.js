'use strict';
//angular.module('FittFinderApp', ['ngFileUpload']);
ClubhouseNowApp.filter('startFrom', function() {
     return function(input, start) {
          if (input) {
               start = +start; //parse to int
               return input.slice(start);
          }
          return [];
     }
});

ClubhouseNowApp.controller('ProfileController', function($rootScope,$scope, $http, $timeout, $location, $state, $window, $stateParams, Upload) {
     $scope.$on('$viewContentLoaded', function() {
          // initialize core components
          Metronic.initAjax();
     });
     /*$scope.getRecords = function() {
          $http.get('api/module/users/getAll').success(function(data) {
               $scope.list = data.data;
               $scope.currentPage = 1; //current page
               $scope.entryLimit = 10; //max no of items to display in a page
               $scope.filteredItems = $scope.list.length; //Initially for no filter  
               $scope.totalItems = $scope.list.length;
          });
          $scope.setPage = function(pageNo) {
               $scope.currentPage = pageNo;
          };
          $scope.filter = function() {
               $timeout(function() {
                    $scope.filteredItems = $scope.filtered.length;
               }, 10);
          };
          $scope.sort_by = function(predicate) {
               $scope.predicate = predicate;
               $scope.reverse = !$scope.reverse;
          };
     }
     */
     $scope.initUserProfile = function() {
          $http({
               url: 'api/module/users/getUserProfile/',
               method: "GET"
          }).success(function(data) {
               $scope.userProfile = data.data;
               $scope.filepreview = data.data.profile_image;
               if (data.data.state == ""){
                   data.data.state = "Select";
               }
               $scope.states = {
                    availableOptions: $rootScope.stateoptions,
                    selectedOption: {id: data.data.state}
               };
               $timeout(function(){
                    $scope.$broadcast('dataloaded');
                    $("#dob").datepicker();
               },100);
               $scope.$broadcast('dataloaded');
          });
     };
     
     $scope.change_userpass = function() {
          $scope.error = false;
          var form_data = new FormData();
          for ( var key in $scope.user ) {
               form_data.append(key, $scope.user[key]);
          }
          $http({
               url: 'api/module/users/changePassword/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(data) {
               if(data.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text('Your password has been changed successfully.');
                   $scope.user = "";
               } else {
                    $scope.error_code=data.error_code;
                    $scope.error = true;
                    return;
               }
               $scope.$broadcast('dataloaded');
          });
     }
     
     $scope.$on('dataloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
               $scope.success = false;
               $scope.error = false;
               $('#timezone').select2({
                    placeholder: "Select a Timezone",
                    allowClear: true
               }); 
          }, 0, false);
    })
     
     $scope.Update_profile = function() {
          $scope.error = false;
          var form_data = new FormData();
          //$scope.userProfile.timezone = $scope.timezones.selectedtime.id;
          //console.log($scope.userProfile.timezone);
          for ( var key in $scope.userProfile ) {
               form_data.append(key, $scope.userProfile[key]);
          }
          form_data.append('state', $scope.states.selectedOption.id);
          $http({
               url: 'api/module/users/updateProfile/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(data) {
               if(data.success == 1){ 
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text('Your profile has been updated successfully!');
                   $rootScope.User.name = $scope.userProfile.name;
                   $window.sessionStorage["userInfo"] = JSON.stringify($rootScope.User);
                   $scope.user = "";
               } else {
                    $scope.error_code=data.error_code;
                    $scope.error = true;
                    return;
               }
               $scope.$broadcast('dataloaded');
          });
     };
     $('#profile-img').on('change', function(event) { 
           if (this.files[0] != undefined){
                var file = this.files[0];
                var fileType = file["type"];
                var ValidImageTypes = ["image/jpeg", "image/png", "image/jpg"];
                if ($.inArray(fileType, ValidImageTypes) < 0) {
                     // invalid file type code goes here.
                     swal({
                        title: "Invaild File Format",
                        text: "allowed file format are : jpg, jpeg, png",
                        type: "error",
                        allowEscapeKey: false,
                        showCloseButton: true
                      },
                      function(){ 
                          return true;
                      });
                }
           }
     });

     /*$scope.onFileSelect = function(file) {    console.log(file);
            if (file != null){
            if (!file){ //console.log(this);
                swal({
                    title: "Invaild File Format",
                    text: "allowed file format are : jpg, jpeg, png, gif, bmp",
                    type: "error",
                    allowEscapeKey: false,
                    showCloseButton: true
                  },
                  function(){ 
                      //$("#profile_form").trigger('reset');
                      $("#account-profile").val("");
                      return true;
                  });
            }
        }
     }; */
     
     $scope.changeProfile = function(){ 
        $scope.error = false;
        var url = 'api/module/users/setProfileImage';
        var id = $scope.userProfile.id;
        var file = $scope.file; 
        if (file != null) { 
            Upload.upload({
                url: url,
                data: {profile_image: file, userid: id}
            }).then(function(data) { 
                if (data.data.success == 1){ 
                    $rootScope.User.profilepic = data.data.data;
                    $window.sessionStorage["userInfo"] = JSON.stringify($rootScope.User);
                    $scope.initUserProfile(); 
                } else {
                        console.log(data.data);
                        // invalid file type code goes here.
                        swal({
                           title: "Invaild File Size",
                           text: data.data.error_code,
                           type: "error",
                           allowEscapeKey: false,
                           showCloseButton: true
                         },
                         function(){ 
                             return true;
                         });
                }
            });
        } else { 
            $scope.error_code= 'Invvalid File Type.';
            $scope.error = true;
            $timeout(function(){
               $scope.error = false; 
            }, 5000);
            return;
        }
     }
     
});
