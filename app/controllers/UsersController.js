'use strict';
//angular.module('ClubhouseNowApp', ['ngFileUpload']);
ClubhouseNowApp.filter('startFrom', function() {
     return function(input, start) {
          if (input) {
               start = +start; //parse to int
               return input.slice(start);
          }
          return [];
     }
});

ClubhouseNowApp.controller('UsersController', function($rootScope,$scope, $http, $timeout, $location, $state, $stateParams, Upload) {
     $scope.$on('$viewContentLoaded', function() {
          // initialize core components
          Metronic.initAjax();
     });
     $scope.getRecords = function() {
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
     
     $scope.editUser = function() { 
          $scope.error = false;
          var id = $stateParams.id;
               $http({
                    url: 'api/module/users/getUserdetail/',
                    method: "POST",
                    params: {userid: id}
               }).success(function(data) {
                    $scope.user = data.data; 
                    $timeout(function(){
                         $scope.$broadcast('dataloaded');
                    },100);
               });
     };
     
      $scope.Add_SpeAdmin = function() {
          var id = $stateParams.id;
          var form_data = new FormData();
          
          form_data.append('speid', $scope.allspe.SelectedOption.id);
          for ( var key in $scope.speuser ) {
               form_data.append(key, $scope.speuser[key]);
          }
          form_data.append('speadmin', '1');
          $http({
               url: 'api/module/users/AddUser/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/users');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     $scope.InitUser = function() {
          $scope.user = {
               status:'1'
          };
          $scope.$broadcast('dataloaded');
     };
     
     $scope.InitSPiEdit = function() {
          $scope.error = false;
          var id = $stateParams.id;
          $http({
               url: 'api/module/users/getSpidetail/',
               method: "POST",
               params: {userid: id}
          }).success(function(data) {
               $scope.spi = data.data;
               $scope.timezones = {
                    availableOptions: $rootScope.timezoneoptions,
                    selectedOption: {id: data.data.timezone}
               };
               
               $http.get('api/module/specialities/getAll').success(function(spedata){
                    $scope.specialities = {
                         availableOptions: spedata.data,
                         selectedOption: data.data.specialities
                    };
                    $timeout(function(){
                         $scope.$broadcast('dataloaded');
                         $("#dob").datepicker();
                    },100);
               });
               var ecount = data.data.educations.length;
               if(ecount != 0){
                    $scope.educationarray = [];
                    angular.forEach(data.data.educations, function(value, key) {
                         $scope.evalue = {
                              id: 'education'+key,
                              eid: value.id,
                              coursename:value.coursename,
                              universityname:value.universityname,
                              startyear:value.startyear,
                              endyear:value.endyear,
                         };
                         $scope.educationarray.push( $scope.evalue );
                    });
                    $scope.educations = $scope.educationarray;
               } else {
                    $scope.educations = [{id: 'education1'}];
               }
               
               
          });
     };
     
     
     
     $scope.Add_User = function() {
          var id = $stateParams.id;
          var form_data = new FormData();
          
          for ( var key in $scope.user ) {
               form_data.append(key, $scope.user[key]);
          }
          form_data.append('adduser', '1');
          $http({
               url: 'api/module/users/AddUser/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/users');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };

     /*$scope.editUser = function() {
          $scope.error = false;
          var id = $stateParams.id;
          $http({
               url: 'api/module/users/getUserdetail/',
               method: "POST",
               params: {userid: id}
          }).success(function(data) {
               $scope.user = data.data;
               $scope.timezones = {
                    availableOptions: $rootScope.timezoneoptions,
                    selectedOption: {id: data.data.timezone}
               };
               $scope.$broadcast('dataloaded');
               $http.get('api/module/common/getactivelevel').success(function(leveldata) {
                    $scope.activelevel = {
                         availableOptions: leveldata.data,
                         selectedOption: {id: data.data.active_level}
                    };
                    $scope.$broadcast('dataloaded');
               });
               //$scope.$broadcast('dataloaded');
          });
     }; */
     
     $scope.$on('dataloaded', function() {
          $timeout(function() {
               Metronic.initAjax();
               $('#activelevel').select2({
                    placeholder: "Select Active Level",
                    allowClear: true
               });
               $('#timezone').select2({
                    placeholder: "Select a Timezone",
                    allowClear: true
               });
               $('#allspe').select2({
                    placeholder: "Select a SPE",
                    allowClear: true
               });
               $('#specialities').select2({
                    placeholder: "Select a Specialities",
                    allowClear: true
               });
               
               $scope.addNewEducation = function() {
                 var newItemNo = $scope.educations.length+1;
                 $scope.educations.push({'id':'education'+newItemNo});
               };
               $scope.removeEducation = function() {
                 var lastItem = $scope.educations.length-1;
                 $scope.educations.splice(lastItem);
               };
          }, 0, false);
     })

     
     $scope.Update_User = function() {
          var id = $stateParams.id;
          $http({
               url: 'api/module/users/editUser/',
               method: "POST",
               params: $scope.user
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text(resdata.error_code);
                   $scope.activePath = $location.path('/users'); 
               } else {
                    $scope.error_code=resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     
     $scope.Update_SpeAdmin = function() {
          var id = $stateParams.id;
          $scope.speuser.timezone = $scope.timezones.selectedtime.id;
          $http({
               url: 'api/module/users/editUser/',
               method: "POST",
               params: $scope.speuser
          }).success(function(resdata) {
               if(resdata.success == 1){
                   $scope.success = true; 
                   $(".show-success").text('');
                   $(".show-success").text(resdata.error_code);
                   $scope.activePath = $location.path('/users'); 
               } else {
                    $scope.error_code=resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     };
     $scope.Update_Spi = function() {
          var id = $stateParams.id;
          var form_data = new FormData();
          var e=0;
          console.log($scope.educations);
          angular.forEach($scope.educations, function(value, key) {
                form_data.append("education["+e+"][eid]", value.eid);
                form_data.append("education["+e+"][coursename]", value.coursename);
                form_data.append("education["+e+"][universityname]",value.universityname);
                form_data.append("education["+e+"][startyear]",value.startyear);
                form_data.append("education["+e+"][endyear]",value.endyear);
                e++;
          });
          
          $scope.spi.timezone = $scope.timezones.selectedOption.id;
          for ( var key in $scope.spi ) {
               form_data.append(key, $scope.spi[key]);
          }
          form_data.append('updatespi', '1');
          angular.forEach($scope.specialities.selectedOption, function(value, key) {
                form_data.append("specialities[]",value.id);
          });
          
          $http({
               url: 'api/module/users/editSpiUser/',
               method: "POST",
               transformRequest: angular.identity,
               headers: {'Content-Type': undefined,'Process-Data': false},
               data: form_data
          }).success(function(resdata) {
               if (resdata.success == 1) {
                    $scope.success = true;
                    $(".show-success").text('');
                    $(".show-success").text(resdata.error_code);
                    $scope.activePath = $location.path('/users');
               } else {
                    $scope.error_code = resdata.error_code;
                    $scope.error = true;
                    return;
               }
          });
     }; 
     $scope.onFileSelect = function(file) {
          var id = $stateParams.id;
          $(".profile_error").text(''); 
          if (!file){ $(".profile_error").text('* File Type Invalid'); return}; 
          var url = 'api/module/users/setProfileImage';
          Upload.upload({
              url: url,
              data: {profile_image: file, userid: id}
          }).then(function(resp) {
              $scope.editUser();
          });    
     };
     
     $scope.Delete_User = function(user) {
          var deleteUser = confirm('Are You Absolutely Sure You Want To Delete?');
          if (deleteUser) {
                $http({
                    url: 'api/module/users/deleteUser',
                    method: "POST",
                    params: {userid: user.id}
                }).success(function(resp) {
                    $scope.getRecords();
                });
          }        
      };
});