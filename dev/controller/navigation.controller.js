"use strict";
boann.controller('NavigationController', ['$scope', '$http', '$window', '$state', '$stateParams', function($scope, $http, $window, $state, $stateParams) {
    console.log($state.$current.url.pattern.split("/")[1] + " = case name");
    console.log($state.router.globals.$current.views.mainpage.controller + "Controller is Loaded");

    var URI         =   controler.view + "dashboard/index.php";
    var state       =   $state.$current.url.pattern.split("/")[1];

    me();

    function me(){
      $http.get(URI,{params:{action:"me"}}).then(function(data){
        $scope.me = data.data;
      });  
    }


    $scope.log_out  =   function(data){
        window.location = "./index.php?logout=logout";
    }

}]);