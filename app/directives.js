/***
GLobal Directives
***/

// Route State Load Spinner(used on page or content load)
ClubhouseNowApp.directive('ngSpinnerBar', ['$rootScope',
    function($rootScope) {
        return {
            link: function(scope, element, attrs) {
                // by defult hide the spinner bar
                element.addClass('hide'); // hide spinner bar by default

                // display the spinner bar whenever the route changes(the content part started loading)
                $rootScope.$on('$stateChangeStart', function() {
                    element.removeClass('hide'); // show spinner bar  
                });

                // hide the spinner bar on rounte change success(after the content loaded)
                $rootScope.$on('$stateChangeSuccess', function() {
                    element.addClass('hide'); // hide spinner bar
                    $('body').removeClass('page-on-load'); // remove page loading indicator
                    
                    // auto scorll to page top
                    setTimeout(function () {
                        Metronic.scrollTop(); // scroll to the top on content load
                        Layout.setSidebarMenuActiveLink('match'); // activate selected link in the sidebar menu
                    }, $rootScope.settings.layout.pageAutoScrollOnLoad);                    
                });

                // handle errors
                $rootScope.$on('$stateNotFound', function() {
                    element.addClass('hide'); // hide spinner bar
                });

                // handle errors
                $rootScope.$on('$stateChangeError', function() {
                    element.addClass('hide'); // hide spinner bar
                });
                
                // handle errors
                $rootScope.$on('$modalShown', function() {
                    element.addClass('hide'); // hide spinner bar
                });
                
                
            }
        };
    }
])

// Handle global LINK click
ClubhouseNowApp.directive('a',
    function() {
        return {
            restrict: 'E',
            link: function(scope, elem, attrs) {
                if (attrs.ngClick || attrs.href === '' || attrs.href === '#') {
                    elem.on('click', function(e) {
                        e.preventDefault(); // prevent link click for above criteria
                    });
                }
            }
        };
    });

// Handle Dropdown Hover Plugin Integration
ClubhouseNowApp.directive('dropdownMenuHover', function () {
  return {
    link: function (scope, elem) {
      elem.dropdownHover();
    }
  };  
});
ClubhouseNowApp.directive('fileModel', ['$parse', function ($parse) {
    return {
    restrict: 'A',
    link: function(scope, element, attrs) {
        var model = $parse(attrs.fileModel);
        var modelSetter = model.assign;

        element.bind('change', function(){
            scope.$apply(function(){
                modelSetter(scope, element[0].files[0]);
            });
        });
    }
   };
}]);

ClubhouseNowApp.directive("fileinput", [function() {
    return {
      scope: {
        fileinput: "=",
        filepreview: "="
      },
      link: function(scope, element, attributes) {
        element.bind("change", function(changeEvent) {
          scope.fileinput = changeEvent.target.files[0];
          var reader = new FileReader();
          reader.onload = function(loadEvent) {
              
            var fileType = scope.fileinput.type;
            var ValidImageTypes = ["image/jpeg", "image/png", "image/jpg"];
            if ($.inArray(fileType, ValidImageTypes) < 0) {
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
            } else {
                scope.$apply(function() {
                  scope.filepreview = loadEvent.target.result;
                });
            }
          }
          reader.readAsDataURL(scope.fileinput);
        });
      }
    }
}]);

ClubhouseNowApp.directive('ckEditor', function() {
  return {
    require: 'ngModel',
    link: function(scope, elm, attr, ngModel) {
      var ck = CKEDITOR.replace(elm[0]);
      if (!ngModel) return;
      ck.on('pasteState', function() {
        scope.$apply(function() {
          ngModel.$setViewValue(ck.getData());
        });
      });
      ngModel.$render = function(value) {
        ck.setData(ngModel.$viewValue);
      };
    }
  };
});

ClubhouseNowApp.directive('escapeToPlainText', function() {
     return {
          require: 'ngModel',
          link: function(scope, element, attrs, ngModel) {
               scope.$watch(function() {
                    return ngModel.$modelValue;
               }, function(newValue, oldValue) {
                    if (newValue && newValue.length > 0) {
                         var hasEncodedHTML = newValue.indexOf("&#") > -1;
                         if (hasEncodedHTML) {
                              var encodedValue = newValue;
                              var decodedValue = decodeHTMLtoPlainText(encodedValue);
                              ngModel.$setViewValue(decodedValue);
                              ngModel.$render();
                         }
                    }
               }, true);

               function decodeHTMLtoPlainText(aValue) {
                    var elem = document.createElement('div');
                    elem.innerHTML = aValue;
                    return elem.childNodes[0].nodeValue;
               }

          }
     }
});