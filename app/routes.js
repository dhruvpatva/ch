/* Setup Rounting For All Pages */
ClubhouseNowApp.config(['$stateProvider', '$urlRouterProvider','$locationProvider','$httpProvider', 'USER_ROLES', function($stateProvider, $urlRouterProvider,$locationProvider, $httpProvider , USER_ROLES) {
    
    // Redirect any unmatched url
    $urlRouterProvider.otherwise("/");
     
    $stateProvider
        // Dashboard
        .state('dashboard', {
            url: "/",
            templateUrl: "views/dashboard.html",            
            data: {pageTitle: 'Dashboard', pageSubTitle: 'statistics & reports',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "DashboardController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            './assets/global/plugins/morris/morris.css',
                            './assets/admin/pages/css/tasks.css',
                            
                            './assets/global/plugins/morris/morris.min.js',
                            './assets/global/plugins/morris/raphael-min.js',
                            './assets/global/plugins/jquery.sparkline.min.js',
                            './assets/global/plugins/counterup/jquery.waypoints.min.js',
                            './assets/global/plugins/counterup/jquery.counterup.min.js',

                            './assets/admin/pages/scripts/index3.js',
                            './assets/admin/pages/scripts/tasks.js',
                            
                            
                             'app/controllers/DashboardController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Register
        .state('signup', {
            url: "/createaccount",
            templateUrl: "tpl/signup.html",            
            data: {pageTitle: 'Create Account', pageSubTitle: 'Account',authorizedRoles : [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "SignupController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             './assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
                             './assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js',
                             './assets/admin/pages/scripts/form-wizard.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             'app/controllers/SignupController.js',
                        ] 
                    });
                }]
            }
            
        })
        
        // Register Thank you
        .state('thankyou', {
            url: "/success",
            templateUrl: "tpl/thankyou.html",            
            data: {pageTitle: 'Create Account', pageSubTitle: 'Thank you',authorizedRoles : [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "SignupController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             './assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
                             './assets/global/plugins/bootstrap-wizard/jquery.bootstrap.wizard.min.js',
                             './assets/admin/pages/scripts/form-wizard.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             'app/controllers/SignupController.js',
                        ] 
                    });
                }]
            }
            
        })
        
        // User Listing
        .state('users', {
            url: "/users",
            templateUrl: "views/users/index.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'All users',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                        ] 
                    });
                }]
            }
        })
        
        //Add User 
        .state('adduser', {
            url: "/users/adduser",
            templateUrl: "views/users/adduser.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'Add User',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                        ] 
                    });
                }]
            }
        })
        
        // Edit User
        .state('edituser', {
            url: "/users/edituser/:id",
            templateUrl: "views/users/edituser.html",            
            data: {pageTitle: 'Users', pageSubTitle: 'Edit User',authorizedRoles: [USER_ROLES.admin]},
            controller: "UsersController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/UsersController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                        ] 
                    });
                }]
            }
        })
        
        // User Profile
        .state('profile', {
            url: "/users/account",
            templateUrl: "views/users/account.html",            
            data: {pageTitle: 'Account Settings', pageSubTitle: '',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "ProfileController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                            './assets/global/plugins/morris/morris.css',
                            './assets/admin/pages/css/tasks.css',
                            
                            './assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css',
                            './assets/global/plugins/morris/morris.min.js',
                            './assets/global/plugins/morris/raphael-min.js',
                            './assets/global/plugins/jquery.sparkline.min.js',
                            './assets/global/plugins/counterup/jquery.waypoints.min.js',
                            './assets/global/plugins/counterup/jquery.counterup.min.js',
                            './assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.js',
                            './assets/global/plugins/select2/select2.css',
                            './assets/global/plugins/select2/select2.min.js',
                            './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                            './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                            

                            './assets/admin/pages/scripts/index3.js',
                            './assets/admin/pages/scripts/tasks.js',
                            './assets/admin/pages/css/profile.css',
                            
                             'app/controllers/ProfileController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event Categories
        .state('eventcategories', {
            url: "/eventcategories",
            templateUrl: "views/events/category_list.html",            
            data: {pageTitle: 'Event Categories', pageSubTitle: 'All Event Categories',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event Category Add
        .state('addeventcategory', {
            url: "/eventcategories/add_category",
            templateUrl: "views/events/add_category.html",            
            data: {pageTitle: 'Event Categories', pageSubTitle: 'Add Event Category',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
            
        // Event Category Edit
        .state('editeventcategory', {
            url: "/eventcategories/edit_category/:id",
            templateUrl: "views/events/edit_category.html",            
            data: {pageTitle: 'Event Categories', pageSubTitle: 'Edit Event Category',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event SubCategories
        .state('eventsubcategories', {
            url: "/eventcategories/sub/:id",
            templateUrl: "views/events/subcategory_list.html",            
            data: {pageTitle: 'Event SubCategories', pageSubTitle: 'All Event SubCategories',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event SubCategory Add
        .state('addeventsubcategory', {
            url: "/eventcategories/sub/:id/add_subcategory",
            templateUrl: "views/events/add_subcategory.html",            
            data: {pageTitle: 'Event SubCategories', pageSubTitle: 'Add Event SubCategory',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
         // Event SubCategory Edit
        .state('editeventsubcategory', {
            url: "/eventcategories/sub/:parent/edit_subcategory/:id",
            templateUrl: "views/events/edit_subcategory.html",            
            data: {pageTitle: 'Event SubCategories', pageSubTitle: 'Edit Event SubCategory',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Events
        .state('events', {
            url: "/events",
            templateUrl: "views/events/index.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'All Events',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Add Event
        .state('addevent', {
            url: "/events/addevent",
            templateUrl: "views/events/add.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Add Event',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // Edit Event
        .state('editevent', {
            url: "/events/editevent/:id",
            templateUrl: "views/events/edit.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Edit Event',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css',
                             './assets/global/plugins/ckeditor/ckeditor.js',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js'
                        ] 
                    });
                }]
            }
        })
        
        // All Timeslots
        .state('timeslots', {
            url: "/events/timeslots/:id",
            templateUrl: "views/events/timeslots.html",            
            data: {pageTitle: 'Events Timeslots', pageSubTitle: 'All Event Timeslots',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Add Slot
        .state('addslot', {
            url: "/events/addslot/:id",
            templateUrl: "views/events/addslot.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Add Slot',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                             './assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js'
                        ] 
                    });
                }]
            }
        })
        
        // Edit Slot
        .state('editslot', {
            url: "/events/editslot/:id/:event_id",
            templateUrl: "views/events/editslot.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Edit Slot',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/global/plugins/select2/select2.css',
                             './assets/global/plugins/select2/select2.min.js',
                             './assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker3.min.css',
                             './assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js',
                             './assets/global/plugins/bootstrap-timepicker/css/bootstrap-timepicker.min.css',
                             './assets/global/plugins/bootstrap-timepicker/js/bootstrap-timepicker.js',
                             './assets/global/plugins/ckeditor/ckeditor.js'
                        ] 
                    });
                }]
            }
        })
        
        // Event Slot Time Slots
        .state('slots', {
            url: "/events/slots/:id/:event_id",
            templateUrl: "views/events/slots.html",            
            data: {pageTitle: 'Events', pageSubTitle: 'Slots',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js'
                        ] 
                    });
                }]
            }
        })
        
        // Course Preferences
        .state('course-preferences', {
            url: "/course-preferences",
            templateUrl: "views/course-preferences/index.html",            
            data: {pageTitle: 'Course Preferences', pageSubTitle: '',authorizedRoles: [USER_ROLES.admin, USER_ROLES.enduser]},
            controller: "EventsController",
            resolve: {
                deps: ['$ocLazyLoad', function($ocLazyLoad) {
                    return $ocLazyLoad.load({
                        name: 'ClubhouseNowApp',
                        insertBefore: '#ng_load_plugins_before', // load the above css files before '#ng_load_plugins_before'
                        files: [
                             'app/controllers/EventsController.js',
                             './assets/admin/pages/css/profile.css',
                             
                        ] 
                    });
                }]
            }
        })
        
}]);
