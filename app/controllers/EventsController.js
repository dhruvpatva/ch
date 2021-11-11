'use strict';
ClubhouseNowApp.filter('startFrom', function() {
    return function(input, start) {
        if (input) {
            start = +start; //parse to int
            return input.slice(start);
        }
        return [];
    }
});

ClubhouseNowApp.controller('EventsController', function($rootScope, $scope, $http, $timeout, $location, $state, $stateParams, Upload) {
    $scope.$on('$viewContentLoaded', function() {
        // initialize core components
        //Metronic.initAjax();
    });
    $scope.getEventsCategories = function() {
        $http.get('api/module/events/getAllEventsCategory').success(function(data) {
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

    $scope.Add_EventsCategory = function() {
        var form_data = new FormData();
        for (var key in $scope.category) {
            form_data.append(key, $scope.category[key]);
        }
        $http({
            url: 'api/module/events/addEventsCategory/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/eventcategories');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                return;
            }
        });
    };
    
    $scope.addCategoryLoadData = function() {
        $scope.category = {
            status: '1'
        };
        $scope.$broadcast('dataloaded');
    };
    
   
    $scope.edit_EventsCategory = function() {
        $scope.error = false;
        var id = $stateParams.id;
        $http({
            url: 'api/module/events/getEventsCategory/',
            method: "POST",
            params: {id: id}
        }).success(function(data) {
            $scope.category = data.data; 
            $scope.$broadcast('dataloaded');
        });
    };

    $scope.$on('dataloaded', function() {
        $timeout(function() {
            Metronic.initAjax();
        }, 0, false);
    });

    $scope.Update_EventsCategory = function() {
        var id = $stateParams.id;
        var form_data = new FormData();
        for (var key in $scope.category) {
            form_data.append(key, $scope.category[key]);
        }
        $http({
            url: 'api/module/events/editEventsCategory/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/eventcategories');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                return;
            }
        });
    };
    
    $scope.Delete_EventsCategory = function(data) {
        var index = $scope.list.map(function(category) {
            return category.id;
        }).indexOf(data.id);
        
        var deleteEventCategory = confirm('Are You Absolutely Sure You Want To Delete?');
        if (deleteEventCategory) {
            $http({
                url: 'api/module/events/deleteEventsCategory',
                method: "POST",
                params: {id: data.id}
            }).success(function(resp) {
                $scope.list.splice(index, 1);
            });
        }
    };

    $scope.getEventsSubCategories = function() {
        var id = $stateParams.id;
        $scope.id = id;
        $http({
            url: 'api/module/events/getAllEventsSubcategory/',
            method: "POST",
            params: {id: id}
        }).success(function(data){  
            $scope.list = data.data; 
            if (data.data.length != 0){
                if (data.data[0].parent == null){
                    $scope.parent_category = data.data[0].sub;
                } else {
                    $scope.parent_category = data.data[0].parent;
                    $scope.subtitle = data.data[0].sub;
                }
            }
            //$scope.parent_category = data.data[0].parent;
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
    
    $scope.addSubCategoryLoadData = function() {
        $scope.category = {
            status: '1'
        };
        var id = $stateParams.id;
        $scope.id = id;
        $scope.category.parent_id = id;
        $http({
            url: 'api/module/common/getEventCategoryFromId/',
            method: "POST",
            params: {id: id}
        }).success(function(data){ 
            if (data.data.parent == null){
                $scope.category.parent = data.data.name;
            } else {
                $scope.category.parent = data.data.parent;
                $scope.category.title = data.data.name;
            }
            
        });
        $scope.$broadcast('dataloaded');
    };
    
    $scope.Add_EventsSubCategory = function() {
        var id = $stateParams.id;
        var form_data = new FormData();
        for (var key in $scope.category) {
            form_data.append(key, $scope.category[key]);
        }
        $http({
            url: 'api/module/events/addEventsCategory/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/eventcategories/sub/'+ id);
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                return;
            }
        });
    };
    
    $scope.edit_EventsSubCategory = function() {
        $scope.error = false;
        var id = $stateParams.id;
        $http({
            url: 'api/module/events/getEventsSubCategory/',
            method: "POST",
            params: {id: id}
        }).success(function(data) { 
            $scope.category = data.data; 
            $http({
                url: 'api/module/common/getEventCategoryFromId/',
                method: "POST",
                params: {id: $scope.category.parent_id}
            }).success(function(data){ 
                if (data.data.parent == null){
                    $scope.category.parent = data.data.name;
                } else {
                    $scope.category.parent = data.data.parent;
                    $scope.category.title = data.data.name;
                }
            });
            $scope.$broadcast('dataloaded');
        });
    };
    
    $scope.Update_EventsSubCategory = function() {
        var id = $stateParams.id;
        var form_data = new FormData();
        for (var key in $scope.category) {
            form_data.append(key, $scope.category[key]);
        }
        $http({
            url: 'api/module/events/editEventsCategory/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/eventcategories/sub/' + resdata.parent_id);
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                return;
            }
        });
    };

    
    $scope.addEventLoadData = function() {
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };

        $http.get('api/module/common/geteventcategories').success(function(categoriesdata) {
            $scope.eventscategories = {
                availableOptions: categoriesdata.data,
                selectedOption: {id: categoriesdata.data[0].id}
            };
        });

        $scope.event = {
            is_paid: '1',
            discount_type: '0',
            selectedspe: undefined,
            status: '1'
        };

        $timeout(function() {
            Metronic.initAjax();
            $('#timezone').select2({
                placeholder: "Select a Timezone",
                allowClear: true
            });
            $('#category').select2({
                placeholder: "Select a Category",
                allowClear: true
            });
            $("#offer_start_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_end_date').datetimepicker('setStartDate', fromDate);
                }
            });
            $("#offer_end_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_start_date').datetimepicker('setStartDate', new Date());
                    $('#offer_start_date').datetimepicker('setEndDate', fromDate);
                }
            });
        }, 0, false);
    }

    $scope.Add_Event = function() {
        var form_data = new FormData();
        for (var key in $scope.event) {
            form_data.append(key, $scope.event[key]);
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        form_data.append('category_id', $scope.eventscategories.selectedOption.id);
        $http({
            url: 'api/module/events/addEvent/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/events');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };

    $scope.edit_Event = function() {
        $scope.error = false;
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };
        $http.get('api/module/common/geteventcategories').success(function(categoriesdata) {
            $scope.eventscategories = {
                availableOptions: categoriesdata.data,
                selectedOption: {}
            };
        });

        var id = $stateParams.id;
        $http({
            url: 'api/module/events/getEvent/',
            method: "POST",
            params: {id: id}
        }).success(function(data) {  
            $scope.event = data.data;  
            if($scope.event.discount_type == ""){
                $scope.event.discount_type = '0'; 
            }
            $scope.timezones.selectedOption = {id: data.data.timezone};
            $scope.eventscategories.selectedOption = {id: data.data.category_id};
            $scope.$broadcast('dataclassloaded');
        });
    };

    $scope.$on('dataclassloaded', function() {
        $timeout(function() {
            Metronic.initAjax();
            $('#timezone').select2({
                placeholder: "Select a Timezone",
                allowClear: true
            });
            $('#category').select2({
                placeholder: "Select a Category",
                allowClear: true
            });
            $("#offer_start_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_end_date').datetimepicker('setStartDate', fromDate);
                }
            });
            $("#offer_end_date").datetimepicker({
                isRTL: false,
                format: "mm-dd-yyyy HH:ii P",
                autoclose: true,
                todayBtn: true,
                showMeridian: true,
                viewSelect: "decade",
                startDate: new Date(),
                pickerPosition: "bottom-left"
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date.setTime(selected.date.getTime() + (selected.date.getTimezoneOffset() * 60000)));
                    $('#offer_start_date').datetimepicker('setStartDate', new Date());
                    $('#offer_start_date').datetimepicker('setEndDate', fromDate);
                }
            });
        }, 0, false);
    });

    $scope.Update_Event = function() {
        var form_data = new FormData();
        for (var key in $scope.event) {
            form_data.append(key, $scope.event[key]);
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        form_data.append('category_id', $scope.eventscategories.selectedOption.id);
        $http({
            url: 'api/module/events/editEvent/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/events');
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };

    $scope.getEvents = function() {
        $scope.list = [];
        $scope.libraryTemp = {};
        $scope.totalItemsTemp = {};

        $scope.totalItems = 0;
        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };
        
        getResultsPage(1);
        function getResultsPage(pageNumber) {
            if (!$.isEmptyObject($scope.libraryTemp)) {
                $http({
                    url: 'api/module/events/getAllEvents',
                    method: "POST",
                    params: {search: $scope.search, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            } else {
                $http({
                    url: 'api/module/events/getAllEvents',
                    method: "POST",
                    params: {page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            }
        }
        
        $scope.searchDB = function(){
            if($scope.search.length >= 3){
                if($.isEmptyObject($scope.libraryTemp)){
                    $scope.libraryTemp = $scope.list;
                    $scope.totalItemsTemp = $scope.totalItems;
                    $scope.list = {};
                }
                getResultsPage(1);
            }else{
                if(! $.isEmptyObject($scope.libraryTemp)){
                    $scope.list = $scope.libraryTemp ;
                    $scope.totalItems = $scope.totalItemsTemp;
                    $scope.libraryTemp = {};
                }
            }
        }
    }

    $scope.Delete_Event = function(data) {
        var index = $scope.list.map(function(event_single) {
            return event_single.id;
        }).indexOf(data.id);

        var deleteEvent = confirm('Are You Absolutely Sure You Want To Delete?');
        if (deleteEvent) {
            $http({
                url: 'api/module/events/deleteEvent',
                method: "POST",
                params: {id: data.id}
            }).success(function(resp) {
                $scope.list.splice(index, 1);
            });
        }
    };

    $scope.getEventTimeSlots = function() {
        var id = $stateParams.id;
        $scope.event = {
            id: id
        };
        
        $scope.list = [];
        $scope.libraryTemp = {};
        $scope.totalItemsTemp = {};

        $scope.totalItems = 0;
        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };
        
        getResultsPage(1);
        function getResultsPage(pageNumber) {
            if (!$.isEmptyObject($scope.libraryTemp)) {
                $http({
                    url: 'api/module/events/getAllEventTimeSlots',
                    method: "POST",
                    params: {id: id, search: $scope.search, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            } else {
                $http({
                    url: 'api/module/events/getAllEventTimeSlots',
                    method: "POST",
                    params: {id: id, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            }
        }
        
        $scope.searchDB = function(){
            if($scope.search.length >= 3){
                if($.isEmptyObject($scope.libraryTemp)){
                    $scope.libraryTemp = $scope.list;
                    $scope.totalItemsTemp = $scope.totalItems;
                    $scope.list = {};
                }
                getResultsPage(1);
            }else{
                if(! $.isEmptyObject($scope.libraryTemp)){
                    $scope.list = $scope.libraryTemp ;
                    $scope.totalItems = $scope.totalItemsTemp;
                    $scope.libraryTemp = {};
                }
            }
        }
    }

    $scope.addSlotLoadData = function() {
        var event_id = $stateParams.id;
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };

        $scope.slot = {
            status: '1',
            event_id: event_id,
            slot_type: '0',
            recurring_type: '1',
            recurring_months_base: '1',
            attendee_type: '0'
        };

        $("#start_date").datepicker({
            format: "mm-dd-yyyy",
            autoclose: true,
            startDate: '0d'
        }).on('hide', function(selected) {
            if (selected.date !== null) {
                var fromDate = new Date(selected.date);
                $('#end_date').datepicker('setStartDate', fromDate);
            }
        });
        
        $("#end_date").datepicker({
            format: "mm-dd-yyyy",
            autoclose: true,
            startDate: '0d'
        }).on('hide', function(selected) {
            if (selected.date !== null) {
                var fromDate = new Date(selected.date);
                $('#start_date').datepicker('setStartDate', new Date());
                $('#start_date').datepicker('setEndDate', fromDate);
            }
        });
        
        $('#start_time, #end_time').timepicker({
            autoclose: true,
            minuteStep: 5
        });

        $timeout(function() {
            Metronic.initAjax();
            $('#timezone').select2({
                placeholder: "Select a Timezone",
                allowClear: true
            });
        }, 0, false);
    }
    
    $scope.Add_TimeSlot = function() {
        var form_data = new FormData();
        for (var key in $scope.slot) {
            if(key === "recurring_weeks"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_weeks[]', rkey);
                    }
                }
            } else if(key === "recurring_month_date"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_date[]', rkey);
                    }
                }
            } else if(key === "recurring_month_every"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_every[]', rkey);
                    }
                }
            } else if(key === "recurring_month_day"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_day[]', rkey);
                    }
                }
            } else {
                form_data.append(key, $scope.slot[key]);
            }
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        $http({
            url: 'api/module/events/addTimeSlot/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/events/timeslots/'+$scope.slot.event_id);
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };
    
    $scope.editSlotLoadData = function() {
        var event_id = $stateParams.event_id;
        var id = $stateParams.id;
        $scope.timezones = {
            availableOptions: $rootScope.timezoneoptions,
            selectedOption: {id: 'US/Mountain'}
        };

        $scope.slot = {
            status: '1',
            event_id: event_id,
            id: id,
            slot_type: '0',
            recurring_type: '1',
            recurring_months_base: '1',
            attendee_type: '0'
        };

        $http({
            url: 'api/module/events/getEditSlot/',
            method: "POST",
            params: {id: id, event_id: event_id}
        }).success(function(data) {
            $scope.slot = data.data;
            $scope.timezones.selectedOption = {id: data.data.timezone};
            $scope.$broadcast('dataslotloaded');
        });
    };

    $scope.$on('dataslotloaded', function() {
        $timeout(function() {
            Metronic.initAjax();
            $('#timezone').select2({
                placeholder: "Select a Timezone",
                allowClear: true
            });
            $("#start_date").datepicker({
                format: "mm-dd-yyyy",
                autoclose: true,
                startDate: '0d'
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date);
                    $('#end_date').datepicker('setStartDate', fromDate);
                }
            });

            $("#end_date").datepicker({
                format: "mm-dd-yyyy",
                autoclose: true,
                startDate: '0d'
            }).on('hide', function(selected) {
                if (selected.date !== null) {
                    var fromDate = new Date(selected.date);
                    $('#start_date').datepicker('setStartDate', new Date());
                    $('#start_date').datepicker('setEndDate', fromDate);
                }
            });

            $('#start_time, #end_time').timepicker({
                autoclose: true,
                minuteStep: 5
            });
        }, 0, false);
    });
    
    $scope.Edit_TimeSlot = function() {
        var form_data = new FormData();
        for (var key in $scope.slot) {
            if(key === "recurring_weeks"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_weeks[]', rkey);
                    }
                }
            } else if(key === "recurring_month_date"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_date[]', rkey);
                    }
                }
            } else if(key === "recurring_month_every"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_every[]', rkey);
                    }
                }
            } else if(key === "recurring_month_day"){
                for (var rkey in $scope.slot[key]) {
                    if($scope.slot[key][rkey]) {
                        form_data.append('recurring_month_day[]', rkey);
                    }
                }
            } else {
                form_data.append(key, $scope.slot[key]);
            }
        }
        form_data.append('timezone', $scope.timezones.selectedOption.id);
        $http({
            url: 'api/module/events/editTimeSlot/',
            method: "POST",
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined, 'Process-Data': false},
            data: form_data
        }).success(function(resdata) {
            if (resdata.success == 1) {
                $scope.success = true;
                $(".show-success").text('');
                $(".show-success").text(resdata.error_code);
                $scope.activePath = $location.path('/events/timeslots/'+$scope.slot.event_id);
            } else {
                $scope.error_code = resdata.error_code;
                $scope.error = true;
                $("html, body").animate({scrollTop: 0}, "slow");
                return;
            }
        });
    };
    
    $scope.Delete_TimeSlot = function(data) {
        var index = $scope.list.map(function(slot_single) {
            return slot_single.id;
        }).indexOf(data.id);

        var deleteEvent = confirm('Are You Absolutely Sure You Want To Delete?');
        if (deleteEvent) {
            $http({
                url: 'api/module/events/deleteTimeSlot',
                method: "POST",
                params: {id: data.id}
            }).success(function(resp) {
                $scope.list.splice(index, 1);
            });
        }
    };
    
    $scope.getSlots = function() {
        var id = $stateParams.id;
        var event_id = $stateParams.event_id;
        $scope.slot = {
            id: id,
            event_id: event_id
        };
        
        $scope.list = [];
        $scope.libraryTemp = {};
        $scope.totalItemsTemp = {};

        $scope.totalItems = 0;
        $scope.pageChanged = function(newPage) {
            getResultsPage(newPage);
        };
        
        getResultsPage(1);
        function getResultsPage(pageNumber) {
            if (!$.isEmptyObject($scope.libraryTemp)) {
                $http({
                    url: 'api/module/events/getSlots',
                    method: "POST",
                    params: {id: id, event_id: event_id, search: $scope.search, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            } else {
                $http({
                    url: 'api/module/events/getSlots',
                    method: "POST",
                    params: {id: id, event_id: event_id, page: pageNumber}
                }).success(function(data) {
                    $scope.list = data.data;
                    $scope.totalItems = data.total;
                });
            }
        }
        
        $scope.searchDB = function(){
            if($scope.search.length >= 3){
                if($.isEmptyObject($scope.libraryTemp)){
                    $scope.libraryTemp = $scope.list;
                    $scope.totalItemsTemp = $scope.totalItems;
                    $scope.list = {};
                }
                getResultsPage(1);
            }else{
                if(! $.isEmptyObject($scope.libraryTemp)){
                    $scope.list = $scope.libraryTemp ;
                    $scope.totalItems = $scope.totalItemsTemp;
                    $scope.libraryTemp = {};
                }
            }
        }
    }
    
    $scope.onFileSelect = function(file) {
         var id = $stateParams.id;
         $(".profile_error").text('');  
         if ($('input[type="file"]').val()){
            if (!file){ $(".profile_error").text('* File Type Invalid'); return}; 
         }
         return;
     };
});