"use strict";
boann.controller('LoginController', ['$scope', '$http', '$window', '$state', function($scope, $http, $window, $state) {
    var URI         =   controler.view + "home/index.php";
    $scope.login    =   function(data){
        if(data){
            var VALUES  = [{data:data}];
            $http.post(URI, VALUES, {params:{action:"login"}}).then(function(data){
                switch(data.data.data){
                    case "success" :
                        swal("Well done!!", data.data.dataContent, "success");
                        $state.go("dashboard");                  
                    break;

                    case "error" :
                        swal("Oeps!", data.data.dataContent, "error");
                    break;
                }
            });
        }else{
                swal("Oeps!", "All fields are required", "error");            
        };
    };
}]);